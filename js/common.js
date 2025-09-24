'use strict';

$(document).ready(function () {
  $('.drawer').drawer();
});

$(function () {

    $('.Toggle').click(function () {
        $(this).toggleClass('active');
        /*ナビをアニメーションで上に隠す*/
        if ($(this).hasClass('active')) {
            $('.NavMenu').slideToggle().addClass('active'); //クラスを付与
        } else {
            $('.NavMenu').slideToggle().removeClass('active'); //クラスを外す
        }

    });
});

// トップページ スライドショー
$(function () {
  $('.slider').slick({
    autoplay: true,
    autoplaySpeed: 3000,
    fade: true,
    speed: 500,
    // dots: true,
  });
})


// トップへ戻るボタン
$(function(){
  const pagetop = $('#page-top');
  pagetop.hide();
  $(window).scroll(function () {
     if ($(this).scrollTop() > 200) {
          pagetop.fadeIn();
     } else {
          pagetop.fadeOut();
     }
  });
  pagetop.click(function () {
     $('body, html').animate({ scrollTop: 0 }, 500);
     return false;
  });
});

// お問い合わせフォーム
// 必須項目
// お名前
$(function () {
    $("form").on('submit', (function () {
        if ($("input[name='name']").val() === "") {
            if ($("span").attr('class') !== 'name') {
                //if ($(!"span").hasClass('name')) {
                $("input[name='name']").css({
                  "border": "1px solid red",
                  "background": "rgb(245, 197, 197)"
                });
            }
            return false;
        }
    }));
});
// フリガナ
$(function () {
    $("form").on('submit', (function () {
        if ($("input[name='furigana']").val() === "") {
            if ($("span").attr('class') !== 'furigana') {
                $("input[name='furigana']").css({
                  "border": "1px solid red",
                  "background": "rgb(245, 197, 197)"
                });
            }
            return false;
        }
    }));
});
// メール
$(function () {
    $("form").on('submit', (function () {
        if ($("input[name='mail']").val() === "") {
            if ($("span").attr('class') !== 'mail') {
                $("input[name='mail']").css({
                  "border": "1px solid red",
                  "background": "rgb(245, 197, 197)"
                });
            }
            return false;
        }
    }));
});
// セレクトボックス
$(function () {
  $("form").on('submit', function(){
      if($(":selected").attr("value") === "選択してください"){
        if ($("span").attr('class') !== 'messageType') {
          $("select").css({
          "border": "1px solid red",
          "background": "rgb(245, 197, 197)"
        });
      }
        return false;
      }
  });
});
// お問い合わせ内容
$(function () {
    $("form").on('submit', (function () {
        if ($("textarea").val() === "") {
            if ($("span").attr('class') !== 'yourInquiry') {
                $("textarea").css({
                  "border": "1px solid red",
                  "background": "rgb(245, 197, 197)"
                });
            }
            return false;
        }
    }));
});

// 個人情報同意にチェックで送信可能
$(function () {
  $('.checkbox').change(function(){
    if (this.checked) {
      $('button').prop("disabled", false);
    } else {
      $('button').prop("disabled", true);
    }
  });
});