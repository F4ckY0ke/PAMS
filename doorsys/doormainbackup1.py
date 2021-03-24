import base64
import sys
import time
import cv2
from PyQt5.QtCore import *
from PyQt5.QtGui import *
from PyQt5.QtWidgets import *
from aip import AipFace
from PAMS.doorsys.doorui import *

timeout = 3
APP_ID = '23131037'
API_KEY = 'zjgnBoRSR6xn7PA07kDGfcat'
SECRET_KEY = '4TowiYl4zRGTW4GZcil5Cmvp6Ev089NE'
face_score = 90


class MainWindow(QTabWidget, Ui_DOORSYS):
    timecountsin = pyqtSignal()
    cutcamerasin = pyqtSignal()
    Face_checksin = pyqtSignal()

    def __init__(self, parent=None):
        super(MainWindow, self).__init__(parent)
        self.setupUi(self)

        self.setCurrentIndex(0)  # 设置广告页为首页

        self.currentChanged.connect(self.checkTab)  # 绑定单击标签事件


        self.timer_camera = QTimer()
        self.timer_flag = QTimer()
        self.counttime = QTimer()
        self.onesecond = QTimer()
        self.timer_camera.timeout.connect(self.show_camera)
        self.timer_flag.timeout.connect(self.check_face)
        self.counttime.timeout.connect(self.show_tips)
        self.onesecond.timeout.connect(self.checkFaceSuccess)
        self.timecountsin.connect(self.count_tips)
        self.cutcamerasin.connect(self.cut_camera)
        self.Face_checksin.connect(self.FaceInfo)

        # self.tips = QLabel("(%s)秒钟" % (self.time_count))

    def checkTab(self, x):
        # 下标从0开始
        if x == 0:
            print('当前标签是 tab1', x)
        elif x == 1:
            self.time_count = timeout  # 初始化计时器
            self.face_flag = 0  # 初始化人脸标志
            self.FACETIP.setText("正在等待人脸")
            self.FACEIMG.setText("加载中...")
            self.cap = cv2.VideoCapture(0)  # 初始化摄像头
            self.face = cv2.CascadeClassifier(r'haarcascade_frontalface_alt2.xml')
            # self.pushButton.clicked.connect(self.show_camera)
            self.timer_camera.start(1)
            self.timer_flag.start(1)
        else:
            print(x)

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
        """ 你的 APPID AK SK """

        client = AipFace(APP_ID, API_KEY, SECRET_KEY)

        with open("Face.jpeg", "rb") as f:
            # b64encode是编码，b64decode是解码
            image = base64.b64encode(f.read())
            # base64.b64decode(base64data)
            image = str(image, encoding="utf-8")

        imageType = "BASE64"

        groupIdList = "user1"

        options = {}

        """ 调用人脸搜索 """
        list1 = client.search(image, imageType, groupIdList, options)
        scorelist = []
        if 'error_code' in list1:
            if list1['error_code'] == 0:
                for i in range(0, len(list1['result']['user_list'])):
                    scorelist.append(list1['result']['user_list'][i]['score'])
                maxscore = max(scorelist)
                if maxscore >= 90:
                    print(list1['result']['user_list'][scorelist.index(maxscore)]['user_id'])  # 查询数据库验证用户合法性
                    self.FACEIMG.setText("欢迎回家")  # 发送开门指令
                    self.FACETIP.setText("")
                    self.onesecond.start(1500)




if __name__ == '__main__':
    app = QApplication(sys.argv)
    window = MainWindow()
    window.show()
    sys.exit(app.exec_())
