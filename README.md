# pose-recommend-App

- 此處的程式碼是Server中用於處理HTTP Request的php以及進行相似度運算和合成的python

這是一個姿勢推薦的App <br>
App有兩大功能：
1. 依據使用者選取的性別、場景、姿勢隨機從圖片庫中挑選5張符合照片顯示在螢幕供檢視與下載
2. 使用者拍背景上傳，由App發送圖片至HTTP Server，Server經過相似度分析後從圖片庫中推薦適合的5張圖片連結至App供檢視與下載，還可以進行圖片人像與原背景合成

尚未解決的問題：
1. 同時使用系統時可能會顯示錯照片，這是照片儲存的設定導致

以下為App截圖：

<table>
  <tr>
    <td><img src="https://github.com/kelly-y/pose-recommand-App/blob/master/picture/home.jpg"></td>
    <td><img src="https://github.com/kelly-y/pose-recommand-App/blob/master/picture/home1.jpg"></td>
    <td><img src="https://github.com/kelly-y/pose-recommand-App/blob/master/picture/1.jpg"></td>
    <td><img src="https://github.com/kelly-y/pose-recommand-App/blob/master/picture/11.jpg"></td>
  </tr>
  <tr>
    <td><img src="https://github.com/kelly-y/pose-recommand-App/blob/master/picture/2.jpg"></td>
    <td><img src="https://github.com/kelly-y/pose-recommand-App/blob/master/picture/22.jpg"></td>
    <td><img src="https://github.com/kelly-y/pose-recommand-App/blob/master/picture/3.jpg"></td>
    <td></td>
  </tr>
</table>
