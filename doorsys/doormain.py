import sys
import cv2
from PyQt5.QtCore import *
from PyQt5.QtGui import *
from PyQt5.QtWidgets import *
from aip import AipFace
from PAMS.doorsys.doorui import *
import numpy as np
import requests
import base64
import os
import MySQLdb
from configparser import ConfigParser

cfg = ConfigParser()
cfg.read('shebei.ini')


DBIP = cfg.get('server', 'DBIP')
DBID = cfg.get('server', 'DBID')
DBPWD = cfg.get('server', 'DBPWD')
DBNAME = cfg.get('server', 'DBNAME')
SHEBEIID = cfg.get('server', 'SHEBEIID')
# 打开数据库连接
db = MySQLdb.connect(DBIP, DBID, DBPWD, DBNAME, charset='utf8')

# 使用cursor()方法获取操作游标
cursor = db.cursor()

# SQL 查询语句
sql = "SELECT * FROM A_CANSHU;"
try:
   cursor.execute(sql)   # 执行SQL语句
   results = cursor.fetchall()   # 获取所有记录列表
   for lines in results:
       if lines[0] == 'TIME_OUT':  TIME_OUT = int(lines[1])
       if lines[0] == 'APP_ID':  APP_ID = lines[1]
       if lines[0] == 'API_KEY':  API_KEY = lines[1]
       if lines[0] == 'SECRET_KEY':  SECRET_KEY = lines[1]
       if lines[0] == 'QRAPI_KEY':  QRAPI_KEY = lines[1]
       if lines[0] == 'QRSECRET_KEY':  QRSECRET_KEY = lines[1]
       if lines[0] == 'ACCESS_TOKEN':  ACCESS_TOKEN = lines[1]
       if lines[0] == 'FACE_SCORE':  FACE_SCORE = int(lines[1])
       if lines[0] == 'QUALITY_CONTROL':  QUALITY_CONTROL = lines[1]
       if lines[0] == 'LIVENESS_CONTROL':  LIVENESS_CONTROL = lines[1]

   # 从数据库取，失效的话在下面更新到数据库
except:
   print("Error: 无法生成数据")

# 关闭数据库连接
db.commit()
cursor.close()
db.close()

sys.stdout.flush()  # 清空缓存

QRRS = 0


class MainWindow(QTabWidget, Ui_DOORSYS):
    timecountsin = pyqtSignal()
    cutcamerasin = pyqtSignal()
    Face_checksin = pyqtSignal()

    def __init__(self, parent=None):
        super(MainWindow, self).__init__(parent)
        self.setupUi(self)
        self.setWindowFlags(Qt.FramelessWindowHint)  # 隐藏程序头部
        self.setCurrentIndex(0)  # 设置广告页为首页

        self.currentChanged.connect(self.checkTab)  # 绑定单击标签事件
        self.GUANGGAO.setPixmap(QPixmap('./index.png'))

        self.timer_camera = QTimer()
        self.timer_flag = QTimer()
        self.counttime = QTimer()
        self.onesecond = QTimer()
        self.checkmoshi = QTimer()
        self.timer_camera.timeout.connect(self.show_camera)
        self.timer_flag.timeout.connect(self.check_face)
        self.counttime.timeout.connect(self.show_tips)
        self.onesecond.timeout.connect(self.checkFaceSuccess)
        self.checkmoshi.timeout.connect(self.checkMoshi)
        self.timecountsin.connect(self.count_tips)
        self.cutcamerasin.connect(self.cut_camera)
        self.Face_checksin.connect(self.FaceInfo)
        self.QRCoderTimer = QTimer()
        self.QRtimer_camera = QTimer()
        self.QRtimer_camera.timeout.connect(self.show_QRcamera)
        self.QRCoderTimer.timeout.connect(self.cut_QRcamera)
        self.checkmoshi.start(1)

        # self.tips = QLabel("(%s)秒钟" % (self.time_count))

    def checkMoshi(self):
        self.checkmoshi.stop()
        self.moshi = '5'
        db = MySQLdb.connect(DBIP, DBID, DBPWD, DBNAME, charset='utf8')
        cursor = db.cursor()
        # global SHEBEIID
        # sql = "CALL CHECKRANDOM('" + SHEBEIID + "','" + checkRANDOM + "');"

        # cursor.callproc('CHECKFACE', args=(SHEBEIID, checkFACENAME))
        # 提交到数据库执行
        sql = "select canshuvalue from A_CANSHU WHERE CANSHUNAME = 'GUANLIMOSHI';"
        cursor.execute(sql)
        res2 = cursor.fetchall()
        # results = list(cursor.fetchall())
        # 关闭数据库连接
        db.close()
        self.moshi = res2[0][0]
        if(self.moshi == '1'):
            self.setTabEnabled(1, True)
            self.setTabEnabled(2, True)
        elif(self.moshi == '2'):
            self.setTabEnabled(1, True)
            self.setTabEnabled(2, False)
        elif(self.moshi == '3'):
            self.setTabEnabled(1, False)
            self.setTabEnabled(2, True)
        elif(self.moshi == '4'):
            self.setTabEnabled(1, True)
            self.setTabEnabled(2, True)
        else:
            self.setTabEnabled(1, False)
            self.setTabEnabled(2, False)
        self.checkmoshi.start(5000)


    def checkTab(self, x):
        # 下标从0开始
        if x == 0:
            self.QRtimer_camera.stop()
            self.QRCoderTimer.stop()
            self.timer_camera.stop()
            self.timer_flag.stop()
            self.counttime.stop()
            self.onesecond.stop()
            try:
                self.QRcap.release()
            except:
                pass
            try:
                self.cap.release()
            except:
                pass
            sys.stdout.flush()  # 进入首页进行缓存清理
        elif x == 1:
            self.QRtimer_camera.stop()
            self.QRCoderTimer.stop()
            try:
                self.QRcap.release()
            except:
                pass
            self.time_count = TIME_OUT  # 初始化计时器
            self.face_flag = 0  # 初始化人脸标志
            self.FACETIP.setText("正在等待人脸")
            self.FACEIMG.setText("加载中...")
            self.cap = cv2.VideoCapture(0)  # 初始化摄像头
            self.face = cv2.CascadeClassifier(r'haarcascade_frontalface_alt2.xml')
            # self.pushButton.clicked.connect(self.show_camera)
            self.timer_camera.start(1)
            self.timer_flag.start(1)
        else:
            global QRRS
            QRRS = 0
            self.timer_camera.stop()
            self.timer_flag.stop()
            try:
                self.cap.release()
            except:
                pass
            self.QRTIP.setText("请将二维码对准摄像头")
            self.QRIMG.setText("加载中...")
            self.QRcap = cv2.VideoCapture(0)  # 初始化摄像头
            self.QRtimer_camera.start(1)
            self.QRCoderTimer.start(1000)

    def show_QRcamera(self):
        flag, self.image = self.QRcap.read()
        show = cv2.resize(self.image, (480, 320))
        show = cv2.cvtColor(show, cv2.COLOR_BGR2RGB)  # 转灰
        showImage = QImage(show.data, show.shape[1], show.shape[0], QImage.Format_RGB888)
        self.QRIMG.setPixmap(QPixmap.fromImage(showImage))

    def cut_QRcamera(self):
        sys.stdout.flush()
        file_name = "QRCoder.jpeg"
        cv2.imwrite(file_name, self.image)
        re = RunImg('QRCoder.jpeg')
        if re:
            self.QRCoderTimer.stop()
            self.QRtimer_camera.stop()
            self.QRcap.release()  # 释放摄像头
            self.QRTIP.setText("验证中...")
            global ACCESS_TOKEN
            QRCoder(ACCESS_TOKEN)
            if QRRS == 1:  # 此处返回值判断用户合法性
                self.QRIMG.setText("欢迎光临")  # 发送开门指令
                self.QRTIP.setText("")
                self.onesecond.start(3300)
            elif QRRS == 2:
                self.QRIMG.setText("红码禁入！")  # 发送开门指令
                self.QRTIP.setText("")
                self.onesecond.start(3300)
            else:
                self.QRIMG.setText("失败请重试")  # 发送开门指令
                self.QRTIP.setText("")
                self.onesecond.start(3300)

    def show_camera(self):
        flag, self.image = self.cap.read()
        show = cv2.resize(self.image, (480, 320))
        show = cv2.cvtColor(show, cv2.COLOR_BGR2RGB)  # 转灰
        self.faces = self.face.detectMultiScale(show)
        for (x, y, w, h) in self.faces:
            ss = cv2.rectangle(show, (x, y), (x + w, y + h), (0, 255, 0), 2)
            # print(ss.ndim)
            if ss.ndim == 3:
                # self.timer_face.start(2500)
                # self.timer_tips.start(1000)
                self.face_flag = 1
                self.FACETIP.setText("请注视摄像头%s秒钟" % self.time_count)
        showImage = QImage(show.data, show.shape[1], show.shape[0], QImage.Format_RGB888)
        self.FACEIMG.setPixmap(QPixmap.fromImage(showImage))

    def cut_camera(self):
        file_name = "Face.jpeg"
        cv2.imwrite(file_name, self.image)
        self.timer_camera.stop()
        self.cap.release()
        self.Face_checksin.emit()

    def show_tips(self):
        self.time_count -= 1
        self.FACETIP.setText("请注视摄像头%s秒钟" % self.time_count)
        if self.time_count == 1:
            self.counttime.stop()
            self.cutcamerasin.emit()

    def count_tips(self):
        # self.timer_count = self.time_count - 1
        self.counttime.start(1000)

    def check_face(self):
        if self.face_flag == 1:
            # self.timer_tips.start(1)
            self.timecountsin.emit()
            self.timer_flag.stop()
            # self.timer_camera.stop()

    def checkFaceSuccess(self):
        self.setCurrentIndex(0)
        self.onesecond.stop()

    def FaceInfo(self):
        client = AipFace(APP_ID, API_KEY, SECRET_KEY)
        with open("Face.jpeg", "rb") as f:
            image = base64.b64encode(f.read())
            image = str(image, encoding="utf-8")
        imageType = "BASE64"
        groupIdList = "user1"
        options = {}
        options["quality_control"] = QUALITY_CONTROL
        options["liveness_control"] = LIVENESS_CONTROL
        """ 调用人脸搜索 """
        list1 = client.search(image, imageType, groupIdList, options)
        scorelist = []
        if 'error_code' in list1:
            if list1['error_code'] == 0:
                for i in range(0, len(list1['result']['user_list'])):
                    scorelist.append(list1['result']['user_list'][i]['score'])
                maxscore = max(scorelist)
                if maxscore >= FACE_SCORE:
                    checkFACENAME = list1['result']['user_list'][scorelist.index(maxscore)]['user_id']
                    db = MySQLdb.connect(DBIP, DBID, DBPWD, DBNAME, charset='utf8')
                    cursor = db.cursor()
                    global SHEBEIID
                    cursor.callproc('CHECKFACE', args=(SHEBEIID, checkFACENAME))
                    # 提交到数据库执行
                    results = cursor.fetchall()
                    cursor.execute("select @_CHECKFACE_0")
                    res2 = cursor.fetchall()
                    # 关闭数据库连接
                    db.commit()
                    db.close()
                    USERFLAG = results[0][0]
                    if USERFLAG == 1:
                        self.FACEIMG.setText("欢迎回家")  # 发送开门指令
                        self.FACETIP.setText("")
                        self.onesecond.start(3500)
                    elif USERFLAG == 2:
                        self.FACEIMG.setText("红码禁入！")  # 发送拒绝指令
                        self.FACETIP.setText("")
                        self.onesecond.start(3500)
                    else:
                        self.FACEIMG.setText("人脸认证失败")
                        self.FACETIP.setText("")
                        self.onesecond.start(3500)  # 延迟3.5秒回到主页
                else:
                    self.FACEIMG.setText("人脸认证失败")
                    self.FACETIP.setText("")
                    self.onesecond.start(3500)  # 延迟3.5秒回到主页
            else:
                self.FACEIMG.setText("人脸认证失败")
                self.FACETIP.setText("")
                self.onesecond.start(3500)  # 延迟3.5秒回到主页
        else:
            self.FACEIMG.setText("人脸认证失败")
            self.FACETIP.setText("")
            self.onesecond.start(3500)  # 延迟3.5秒回到主页


def show(code):  # 使用下一句把图像显示出来
# def show(img, code=cv2.COLOR_BGR2RGB):
    return code  # 直接返回二维码帧
    # while (1):
    #  cv2.imshow('ckh',img)
    #  key = cv2.waitKey(10)
    #  c = chr(key & 255)
    #  if c in ['B', 'b', chr(27)]:
    #      break


def createLineIterator(P1, P2, img):
    """
    生成由两点之间的直线上每个像素的坐标和强度组成的数组

    参数:
        -P1:由第一个点（x，y）的坐标组成的numpy数组
        -P2:由第二个点（x，y）的坐标组成的numpy数组
        -img: 正在处理的图像

    返回值:
        -it: 由半径中每个像素的坐标和强度组成的numpy数组（shape:[numPixels，3]，row=[x，y，intensity]）
    """
    # 为可读性定义局部变量
    imageH = img.shape[0]
    imageW = img.shape[1]
    P1X = P1[0]
    P1Y = P1[1]
    P2X = P2[0]
    P2Y = P2[1]

    # 点间差和绝对差
    # 用于计算点之间的坡度和相对位置
    dX = P2X - P1X
    dY = P2Y - P1Y
    dXa = np.abs(dX)
    dYa = np.abs(dY)

    # 基于点间距离预定义numpy数组输出
    itbuffer = np.empty(shape=(np.maximum(dYa, dXa), 3), dtype=np.float32)
    itbuffer.fill(np.nan)

    # 基于点间距离预定义numpy数组输出
    negY = P1Y > P2Y
    negX = P1X > P2X
    if P1X == P2X:  # 垂直线段
        itbuffer[:, 0] = P1X
        if negY:
            itbuffer[:, 1] = np.arange(P1Y - 1, P1Y - dYa - 1, -1)
        else:
            itbuffer[:, 1] = np.arange(P1Y+1, P1Y+dYa+1)
    elif P1Y == P2Y:  # 水平线段
        itbuffer[:, 1] = P1Y
        if negX:
            itbuffer[:, 0] = np.arange(P1X-1, P1X-dXa-1, -1)
        else:
            itbuffer[:, 0] = np.arange(P1X+1, P1X+dXa+1)
    else:  # 对角线段
        steepSlope = dYa > dXa
        if steepSlope:
            slope = dX.astype(np.float32)/dY.astype(np.float32)
            if negY:
                itbuffer[:, 1] = np.arange(P1Y-1, P1Y-dYa-1, -1)
            else:
                itbuffer[:, 1] = np.arange(P1Y+1, P1Y+dYa+1)
            itbuffer[:, 0] = (slope*(itbuffer[:, 1]-P1Y)).astype(int) + P1X
        else:
            slope = dY.astype(np.float32)/dX.astype(np.float32)
            if negX:
                itbuffer[:, 0] = np.arange(P1X-1, P1X-dXa-1, -1)
            else:
                itbuffer[:, 0] = np.arange(P1X+1, P1X+dXa+1)
            itbuffer[:, 1] = (slope*(itbuffer[:, 0]-P1X)).astype(int) + P1Y

    # 删除图像外的点
    colX = itbuffer[:, 0]
    colY = itbuffer[:, 1]
    itbuffer = itbuffer[(colX >= 0) & (colY >= 0) & (colX < imageW) & (colY < imageH)]

    # 从img ndarray获取强度
    itbuffer[:, 2] = img[itbuffer[:, 1].astype(np.uint), itbuffer[:, 0].astype(np.uint)]

    return itbuffer


def isTimingPattern(line):
    # 除去开头结尾的白色像素点
    while line[0] != 0:
        line = line[1:]
    while line[-1] != 0:
        line = line[:-1]
    # 计数连续的黑白像素点
    c = []
    count = 1
    l = line[0]
    for p in line[1:]:
        if p == l:
            count = count + 1
        else:
            c.append(count)
            count = 1
        l = p
    c.append(count)
    # 如果黑白间隔太少，直接排除
    if len(c) < 5:
        return False
    # 计算方差，根据离散程度判断是否是 Timing Pattern
    threshold = 5
    return np.var(c) < threshold


def cv_distance(P, Q):
    return int(np.math.sqrt(pow((P[0] - Q[0]), 2) + pow((P[1] - Q[1]), 2)))


def check(a, b, path):
    # 存储 ab 数组里最短的两点的组合
    s1_ab = ()
    s2_ab = ()
    # 存储 ab 数组里最短的两点的距离，用于比较
    s1 = np.iinfo('i').max
    s2 = s1
    for ai in a:
        for bi in b:
            d = cv_distance(ai, bi)
            if d < s2:
                if d < s1:
                    s1_ab, s2_ab = (ai, bi), s1_ab
                    s1, s2 = d, s1
                else:
                    s2_ab = (ai, bi)
                    s2 = d

    a1, a2 = s1_ab[0], s2_ab[0]
    b1, b2 = s1_ab[1], s2_ab[1]

    a1 = (a1[0] + np.int0((a2[0]-a1[0])*1/14), a1[1] + np.int0((a2[1]-a1[1])*1/14))
    b1 = (b1[0] + np.int0((b2[0]-b1[0])*1/14), b1[1] + np.int0((b2[1]-b1[1])*1/14))
    a2 = (a2[0] + np.int0((a1[0]-a2[0])*1/14), a2[1] + np.int0((a1[1]-a2[1])*1/14))
    b2 = (b2[0] + np.int0((b1[0]-b2[0])*1/14), b2[1] + np.int0((b1[1]-b2[1])*1/14))
    img = cv2.imread(path)
    img_gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    th, bi_img = cv2.threshold(img_gray, 100, 255, cv2.THRESH_BINARY)
    # 将最短的两个线画出来
    #cv2.line(draw_img, a1, b1, (0,0,255), 3)
    #cv2.line(draw_img, a2, b2, (0,0,255), 3)
    lit1 = createLineIterator(a1, b1, bi_img)
    lit2 = createLineIterator(a2, b2, bi_img)
    try:
        lit1bool = isTimingPattern(lit1[:, 2])
    except:
        lit1bool = False
    try:
        lit2bool = isTimingPattern(lit2[:, 2])
    except:
        lit2bool = False
    if lit1bool:
        return True
    elif lit2bool:
        return True
    else:
        return False


def RunImg(path):
    img = cv2.imread(path)
    img_gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    img_gb = cv2.GaussianBlur(img_gray, (5, 5), 0)
    edges = cv2.Canny(img_gray, 100, 200)
    contours, hierarchy = cv2.findContours(edges, cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)
    hierarchy = hierarchy[0]
    found = []
    for i in range(len(contours)):
        k = i
        c = 0
        while hierarchy[k][2] != -1:
            k = hierarchy[k][2]
            c = c + 1  # 计数层次结构
        if c >= 5:
            found.append(i)  # 存储索引
            # 对图像进行二值化
    th, bi_img = cv2.threshold(img_gray, 100, 255, cv2.THRESH_BINARY)
    draw_img = img.copy()
    boxes = []
    for i in found:
        rect = cv2.minAreaRect(contours[i])
        box = np.int0(cv2.boxPoints(rect))
        #    cv2.drawContours(draw_img,[box], 0, (0,0,255), 2)
        # box = map(tuple, box)
        box = [tuple(x) for x in box]
        boxes.append(box)
        # show(draw_img)
        # print("Length of Boxes is ",len(boxes))
    valid = set()
    for i in range(len(boxes)):
        for j in range(i + 1, len(boxes)):
            if check(boxes[i], boxes[j], path):
                valid.add(i)
                valid.add(j)
    contour_all = []
    while len(valid) > 0:
        c = contours[found[valid.pop()]]
        for sublist in c:
            for p in sublist:
                contour_all.append(p)
    try:
        rect = cv2.minAreaRect(np.array(contour_all))
    except:
        return 0
    else:
        box = np.array([cv2.boxPoints(rect)], dtype=np.int0)
        cv2.polylines(draw_img, box, True, (0, 0, 255), 3)
        return 1


def QRCoder(ACCESS_TOKEN):
    sys.stdout.flush()
    request_url = "https://aip.baidubce.com/rest/2.0/ocr/v1/qrcode"
    # 二进制方式打开图片文件
    f = open('QRCoder.jpeg', 'rb')
    img = base64.b64encode(f.read())

    params = {"image": img}

    request_url = request_url + "?access_token=" + ACCESS_TOKEN
    headers = {'content-type': 'application/x-www-form-urlencoded'}
    response = requests.post(request_url, data=params, headers=headers)
    if response:
        jsonCode = response.json()
        if 'error_code' in jsonCode:  # 判断错误接口返回是否有错误代码
            rs = getAccessToken()
            rsAcc = rs['access_token']  # 如果有则更新AccessToken并重新调用
            # 打开数据库连接
            db = MySQLdb.connect(DBIP, DBID, DBPWD, DBNAME, charset='utf8')
            # 使用cursor()方法获取操作游标
            cursor = db.cursor()
            # SQL 查询语句
            sql = "UPDATE A_CANSHU SET CANSHUVALUE = '" + rsAcc + "' WHERE CANSHUNAME = 'ACCESS_TOKEN';"
            try:
                # 执行SQL语句
                cursor.execute(sql)
                # 提交到数据库执行
                db.commit()
            except:
                # 发生错误时回滚
                db.rollback()
            # 关闭数据库连接
            db.close()
            QRCoder(rsAcc)
        elif jsonCode['codes_result_num'] != 1:
            pass
        else:
            checkRANDOM = jsonCode['codes_result'][0]['text'][0]  # 拼接
            global QRRS
            if len(checkRANDOM) != 28:
                QRRS = 0
            else:
                db2 = MySQLdb.connect(DBIP, DBID, DBPWD, DBNAME, charset='utf8')
                cursor = db2.cursor()
                global SHEBEIID
                cursor.callproc('CHECKRANDOM', args=(SHEBEIID, checkRANDOM))
                # 提交到数据库执行
                results = cursor.fetchall()
                cursor.execute("select @_CHECKRANDOM_0")
                res2 = cursor.fetchall()
                # 关闭数据库连接
                db2.commit()
                db2.close()
                QRRS = results[0][0]  # 查询数据库验证用户合法性
    else:
        restart_program()


def getAccessToken():  # AccessToken过期时使用API_KEY和SKEY更新AccessToken
    host = 'https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id='+QRAPI_KEY+'&client_secret='+QRSECRET_KEY
    response = requests.get(host)
    if response:
        rs = response.json()
        return rs


def restart_program():
  python = sys.executable
  os.execl(python, python, * sys.argv)


def python_program():  # 主程序
    app = QApplication(sys.argv)
    window = MainWindow()
    window.show()
    sys.exit(app.exec_())


if __name__ == '__main__':
    while True:
        try:
            python_program()
        except:
            continue
