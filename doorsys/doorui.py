# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'doorui.ui'
#
# Created by: PyQt5 UI code generator 5.10.1
#
# WARNING! All changes made in this file will be lost!

from PyQt5 import QtCore, QtGui, QtWidgets

class Ui_DOORSYS(object):
    def setupUi(self, DOORSYS):
        DOORSYS.setObjectName("DOORSYS")
        DOORSYS.resize(506, 465)
        font = QtGui.QFont()
        font.setFamily("幼圆")
        font.setPointSize(16)
        DOORSYS.setFont(font)
        DOORSYS.setStyleSheet("QTabBar::tab{width:168px;height:60px;}")
        DOORSYS.setTabPosition(QtWidgets.QTabWidget.South)
        self.INDEXTAB = QtWidgets.QWidget()
        self.INDEXTAB.setObjectName("INDEXTAB")
        self.GUANGGAO = QtWidgets.QLabel(self.INDEXTAB)
        self.GUANGGAO.setGeometry(QtCore.QRect(10, 10, 480, 381))
        font = QtGui.QFont()
        font.setFamily("幼圆")
        font.setPointSize(48)
        self.GUANGGAO.setFont(font)
        self.GUANGGAO.setMouseTracking(False)
        self.GUANGGAO.setTabletTracking(False)
        self.GUANGGAO.setFocusPolicy(QtCore.Qt.NoFocus)
        self.GUANGGAO.setAcceptDrops(False)
        self.GUANGGAO.setLayoutDirection(QtCore.Qt.LeftToRight)
        self.GUANGGAO.setAutoFillBackground(False)
        self.GUANGGAO.setStyleSheet("color: rgb(255, 0, 0);\n"
"border-width: 1px;border-style: solid;\n"
"border-color: rgb(122, 139, 119);\n"
"background-color: rgb(125, 125, 125);\n"
"image: url(:/image/index.png);")
        self.GUANGGAO.setText("")
        self.GUANGGAO.setAlignment(QtCore.Qt.AlignCenter)
        self.GUANGGAO.setObjectName("GUANGGAO")
        DOORSYS.addTab(self.INDEXTAB, "")
        self.FACETAB = QtWidgets.QWidget()
        self.FACETAB.setObjectName("FACETAB")
        self.FACEIMG = QtWidgets.QLabel(self.FACETAB)
        self.FACEIMG.setGeometry(QtCore.QRect(10, 10, 480, 320))
        font = QtGui.QFont()
        font.setFamily("幼圆")
        font.setPointSize(48)
        self.FACEIMG.setFont(font)
        self.FACEIMG.setMouseTracking(False)
        self.FACEIMG.setTabletTracking(False)
        self.FACEIMG.setFocusPolicy(QtCore.Qt.NoFocus)
        self.FACEIMG.setAcceptDrops(False)
        self.FACEIMG.setLayoutDirection(QtCore.Qt.LeftToRight)
        self.FACEIMG.setAutoFillBackground(False)
        self.FACEIMG.setStyleSheet("color: rgb(255, 0, 0);\n"
"border-width: 1px;border-style: solid;\n"
"border-color: rgb(122, 139, 119);")
        self.FACEIMG.setAlignment(QtCore.Qt.AlignCenter)
        self.FACEIMG.setObjectName("FACEIMG")
        self.FACETIP = QtWidgets.QLabel(self.FACETAB)
        self.FACETIP.setGeometry(QtCore.QRect(10, 340, 471, 51))
        font = QtGui.QFont()
        font.setFamily("幼圆")
        font.setPointSize(24)
        self.FACETIP.setFont(font)
        self.FACETIP.setStyleSheet("color: rgb(255, 0, 0);")
        self.FACETIP.setAlignment(QtCore.Qt.AlignCenter)
        self.FACETIP.setObjectName("FACETIP")
        DOORSYS.addTab(self.FACETAB, "")
        self.QRTAB = QtWidgets.QWidget()
        self.QRTAB.setObjectName("QRTAB")
        self.QRTIP = QtWidgets.QLabel(self.QRTAB)
        self.QRTIP.setGeometry(QtCore.QRect(10, 340, 471, 51))
        font = QtGui.QFont()
        font.setFamily("幼圆")
        font.setPointSize(24)
        self.QRTIP.setFont(font)
        self.QRTIP.setStyleSheet("color: rgb(255, 0, 0);")
        self.QRTIP.setAlignment(QtCore.Qt.AlignCenter)
        self.QRTIP.setObjectName("QRTIP")
        self.QRIMG = QtWidgets.QLabel(self.QRTAB)
        self.QRIMG.setGeometry(QtCore.QRect(10, 10, 480, 320))
        font = QtGui.QFont()
        font.setFamily("幼圆")
        font.setPointSize(48)
        self.QRIMG.setFont(font)
        self.QRIMG.setMouseTracking(False)
        self.QRIMG.setTabletTracking(False)
        self.QRIMG.setFocusPolicy(QtCore.Qt.NoFocus)
        self.QRIMG.setAcceptDrops(False)
        self.QRIMG.setLayoutDirection(QtCore.Qt.LeftToRight)
        self.QRIMG.setAutoFillBackground(False)
        self.QRIMG.setStyleSheet("color: rgb(255, 0, 0);\n"
"border-width: 1px;border-style: solid;\n"
"border-color: rgb(122, 139, 119);")
        self.QRIMG.setAlignment(QtCore.Qt.AlignCenter)
        self.QRIMG.setObjectName("QRIMG")
        DOORSYS.addTab(self.QRTAB, "")

        self.retranslateUi(DOORSYS)
        DOORSYS.setCurrentIndex(0)
        QtCore.QMetaObject.connectSlotsByName(DOORSYS)

    def retranslateUi(self, DOORSYS):
        _translate = QtCore.QCoreApplication.translate
        DOORSYS.setWindowTitle(_translate("DOORSYS", "TabWidget"))
        DOORSYS.setTabText(DOORSYS.indexOf(self.INDEXTAB), _translate("DOORSYS", "首  页"))
        self.FACEIMG.setText(_translate("DOORSYS", "加载中..."))
        self.FACETIP.setText(_translate("DOORSYS", "加载中..."))
        DOORSYS.setTabText(DOORSYS.indexOf(self.FACETAB), _translate("DOORSYS", "人脸认证"))
        self.QRTIP.setText(_translate("DOORSYS", "加载中..."))
        self.QRIMG.setText(_translate("DOORSYS", "加载中..."))
        DOORSYS.setTabText(DOORSYS.indexOf(self.QRTAB), _translate("DOORSYS", "二维码认证"))

import image_rc
