import cv2
import numpy as np
from PyQt5.QtGui import *
from PyQt5.QtWidgets import *
from PyQt5.QtCore import *
from PAMS.Door.cameraui import *
import os
import sys
import time
import requests
import base64
import json

QRAPI_KEY = 'KnlmM8wRl87ZsI4MWu8lCQg0'
QRSECRET_KEY = '3nMrgQDwEkmlH0ili0me5yg6Ild1idLk'


sys.stdout.flush()  # 清空缓存


class MainWindow(QMainWindow, Ui_MainWindow):
    def __init__(self, parent=None):
        super(MainWindow, self).__init__(parent)
        self.setupUi(self)

        self.QRCoder = QTimer()
        self.QRtimer_camera = QTimer()
        self.QRtimer_camera.timeout.connect(self.show_QRcamera)
        self.QRCoder.timeout.connect(self.cut_QRcamera)

        self.QRcap = cv2.VideoCapture(0)  # 初始化摄像头
        self.QRtimer_camera.start(1)
        self.QRCoder.start(1500)

    def show_QRcamera(self):
        flag, self.image = self.QRcap.read()
        show = cv2.resize(self.image, (480, 320))
        show = cv2.cvtColor(show, cv2.COLOR_BGR2RGB)  # 转灰

        showImage = QImage(show.data, show.shape[1], show.shape[0], QImage.Format_RGB888)
        self.camera.setPixmap(QPixmap.fromImage(showImage))

    def cut_QRcamera(self):
        file_name = "QRCoder.jpeg"
        cv2.imwrite(file_name, self.image)
        re = RunImg('QRCoder.jpeg')
        if re == 1:
            self.QRCoder.stop()
            Access_token = '24.ee9931e9cd4d9a9605be7e2g662f0f49.2592000.1617268773.282335-23730216'  # 从数据库取，失效的话在下面更新到数据库
            QRCoder(Access_token)


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
    itbuffer = np.empty(shape=(np.maximum(dYa,dXa),3),dtype=np.float32)
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
    if isTimingPattern(lit1[:, 2]):
        return True
    elif isTimingPattern(lit2[:, 2]):
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


def QRCoder(Access_token):
    '''
    二维码识别
    '''
    sys.stdout.flush()
    request_url = "https://aip.baidubce.com/rest/2.0/ocr/v1/qrcode"
    # 二进制方式打开图片文件
    f = open('QRCoder.jpeg', 'rb')
    img = base64.b64encode(f.read())

    params = {"image": img}

    request_url = request_url + "?access_token=" + Access_token
    headers = {'content-type': 'application/x-www-form-urlencoded'}
    response = requests.post(request_url, data=params, headers=headers)
    if response:
        jsonCode = response.json()
        if 'error_code' in jsonCode:  # 判断错误接口返回是否有错误代码
            rs = getAccessToken()
            Access_token = rs['access_token']  # 如果有则更新AccessToken并重新调用
            QRCoder(Access_token)
        else:
            print(jsonCode['codes_result'][0]['text'][0])  # 查询数据库验证用户合法性
    else:
        print("error")


def getAccessToken():  # AccessToken过期时使用API_KEY和SKEY更新AccessToken
    host = 'https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id='+QRAPI_KEY+'&client_secret='+QRSECRET_KEY
    response = requests.get(host)
    if response:
        rs = response.json()
        return rs


if __name__ == '__main__':
    app = QApplication(sys.argv)
    window = MainWindow()
    window.show()
    sys.exit(app.exec_())
