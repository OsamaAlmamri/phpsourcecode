$(function(){
	$('[data-toggle="paychannel"]').on('click', function() {
        $(".payment-child").removeClass("selected");
        $(".payment-child").find('i').removeClass("icon-check-circle");
        $(".payment-child").find('i').addClass("icon-circle-blank");
        $(this).find('i').removeClass("icon-circle-blank");
        $(this).find('i').addClass("icon-check-circle");
        $(this).addClass("selected");
        $('[name="channel"]').val($(this).data('channel'));
    });
    $('[data-toggle="paychannel"]:first').trigger("click");//执行一次支付方式选择点击
})

$(function(){
	$("form").submit(function () {
        toast.showLoading();
        
    });
})