</div>
</div>
<div class='container'>
    <div class="row footer" id="contactus">
        <div class="span4">
            <div class="partners mail-icon">تماس با ما</div>
            <div id="contact_response"></div>
            <form class="form-horizontal contact-form">

                <div class="control-group">
                    <label class="control-label" for="inputFamily">نام و نام خانوادگی</label>
                    <div class="controls">
                        <input type="text" name="name" id="inputFamily" placeholder="نام و نام خانوادگی" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputEmail">ایمیل</label>
                    <div class="controls">
                        <input type="text" name="email" id="inputEmail" placeholder="ایمیل" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputComment">متن پیام</label>
                    <div class="controls">
                        <textarea type="text" name="message" id="inputComment" placeholder="متن پیام"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary submit-btn">ارسال پیام</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="span4 partners_area">
        <?php /*    <div class="partners">سایت های همکار</div>
            <a href="#">همکار</a>
            <a href="#">همکار</a>
            <a href="#">همکار</a>*/ ?>
        </div>
		
        <div class="span4 copyright" style="height:280px; position:relative">
        	<img src="/s/img/logo-b.png">
            <div class="links">
                <a href="#">درباره ما</a>
                <a href="#">ﺗﻤﺎﺱ ﺑﺎ ﻣﺎ</a>
                <a href="#">قوانین و شرایط</a>
            </div>
            <div class="copyright-text">&copy; Copyright 2013 AndishehExchange.<br>All Rights Reserved.</div>
        </div>
    </div>
</div>
<?=(($js_file)?'<script type="text/javascript" src="/s/'.$js_file.'"></script>':'')?>
<?=(($js_footer)?'<script type="text/javascript">'.$js_footer.'</script>':'')?>
</body>
</html>