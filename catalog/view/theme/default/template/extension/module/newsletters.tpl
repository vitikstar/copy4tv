<div class="row">
	<h3><?php echo $heading_title; ?></h3>
	<form action="" method="post" class="form-horizontal">
		<div class="form-group" id="form-newsletter-error">
			<label for="input-firstname" class="control-label col-xs-2"><?php echo $text_email; ?></label>
			<div class="col-xs-6" id="newsletter-email">
				<input type="email" name="txtemail" id="txtemail" value="" placeholder="" class="form-control"  />
			</div>
			<span class="col-xs-2">
				<button type="submit" class="btn btn-secondary" onclick="return addNewsletter();"><?php echo $text_submit; ?></button> 
			</span> 
		</div>
	</form>
</div>

<script>
    function addNewsletter() {
        var emailpattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var email = $('#txtemail').val();
        if(email != "") {
            if(!emailpattern.test(email)) {
                $("#text-danger-newsletter").remove();
                $("#form-newsletter-error").removeClass("has-error");
                $("#newsletter-email").append('<div class="text-danger" id="text-danger-newsletter"><?php echo $error_news_email_invalid; ?></div>');
                $("#form-newsletter-error").addClass("has-error");

                return false;
            } else {
                $.ajax({
                    url: 'index.php?route=extension/module/newsletters/add',
                    type: 'post',
                    data: 'email=' + $('#txtemail').val(),
                    dataType: 'json',
                    async:false,
                    success: function(json) {
                        if (json['error']) {
                            $("#newsletter-email").append('<div class="text-danger" id="text-danger-newsletter"><?php echo $error_news_email_invalid; ?></div>');
                            return false;
                        }
                        if (json.message == true) {
                            $('#form-newsletter-error').html('<div class="text-success" id="text-success-newsletter"><?php echo $text_newsletter_sent; ?></div>');
                        } else {
                            $("#text-danger-newsletter").remove();
                            $("#form-newsletter-error").removeClass("has-error");
                            $("#newsletter-email").append(json.message);
                            $("#form-newsletter-error").addClass("has-error");
                        }
                    }
                });
                return false;
            }
        } else {
            $("#text-danger-newsletter").remove();
            $("#form-newsletter-error").removeClass("has-error");
            $("#newsletter-email").append('<div class="text-danger" id="text-danger-newsletter"><?php echo $error_news_email_required; ?></div>');
            $("#form-newsletter-error").addClass("has-error");
            return false;
        }
    }
</script>
<style>
    #text-success-newsletter {
        font-size: 18px;
        text-align: center;
        font-weight: 700;
    }
</style>
