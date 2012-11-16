<?php
/*
Template Name: Contact
*/
?>
<?php 
global $data;

//initialize vars so dont thow errors on 1st time
$nameError = '';
$emailError = '';
$commentError = '';
$titleError = '';

if(isset($_POST['consubmit'])) {
	if(trim($_POST['sender']) === '') {
		$nameError = 'Please enter your name.';
		$hasError = true;
	} else {
		$name = trim($_POST['sender']);
	}

	if(trim($_POST['email']) === '')  {
		$emailError = 'Please enter your email address.';
		$hasError = true;
	} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
		$emailError = 'You entered an invalid email address.';
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}
	
	if(trim($_POST['subject']) === '') {
		$titleError = 'Please enter a title.';
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$title = stripslashes(trim($_POST['subject']));
		} else {
			$title = trim($_POST['subject']);
		}
	}

	if(trim($_POST['content']) === '') {
		$commentError = 'Please enter a message.';
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$comments = stripslashes(trim($_POST['content']));
		} else {
			$comments = trim($_POST['content']);
		}
	}

	if(!isset($hasError)) {
		$emailTo = get_option('tz_email');
		if (!isset($emailTo) || ($emailTo == '') ){
			$emailTo = get_option('admin_email');
		}
		$subject = '[Matrix WordPress Site] From '.$name;
		$body = "Name: $name \n\nEmail: $email \n\nSubject: $title \n\nComments: $comments";
		$headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

		wp_mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
	}

}

get_header(); 
?>

<!-- BEGIN CONTENT -->
<section id="content" class="clearfix">
<!-- Title --><div id="content-title"><?php the_title(); ?></div>

<!-- BEGIN PAGE -->
<section id="page">
<div id="page-img">
<img src="<?php echo $data['matrix_contact_page_image']; ?>" alt="<?php the_title(); ?>" />

<div id="contact-location">

<?php if ( $data['matrix_contact_1_city'] != '' && $data['matrix_contact_1_address'] != '' ) { ?>
<div class="location">
<h5><?php echo $data['matrix_contact_1_city']; ?></h5>
<?php echo $data['matrix_contact_1_address']; ?>

<?php if ( $data['matrix_contact_1_location'] != '' ) { ?>
<a href="<?php echo $data['matrix_contact_1_location']; ?>"><span class="gmap"></span></a>
<?php } ?>

</div>
<?php } ?>

<?php if ( $data['matrix_contact_2_city'] != '' && $data['matrix_contact_2_address'] != '' ) { ?>
<div class="location">
<h5><?php echo $data['matrix_contact_2_city']; ?></h5>
<?php echo $data['matrix_contact_2_address']; ?>

<?php if ( $data['matrix_contact_2_location'] != '' ) { ?>
<a href="<?php echo $data['matrix_contact_2_location']; ?>"><span class="gmap"></span></a>
<?php } ?>

</div>
<?php } ?>

</div><!-- #contact-location -->

</div><!-- #page-img -->

<!-- BEGIN PAGE CONTENT -->
<div id="pg-content" class="clearfix">

<div class="one-half">
<div class="page-sub-title">
<h1>Email Us</h1>
</div>
<p>If you have any questions or comments regarding our services, please do not hesitate to tell us!</p>

<?php if(isset($emailSent) && $emailSent == true) { ?>
							<div class="infobox-green"><span class="infobox-msg">Thanks, your email was sent successfully.</span></div>
<?php } else { ?>
							<?php if(isset($hasError) || isset($captchaError)) { ?>
							<div class="infobox-red"><span class="infobox-msg">Sorry, an error occured.</span></div>
							<?php } ?>

<form action="<?php the_permalink(); ?>" id="contactForm" method="post">
	
	<?php if($nameError != '') { ?>
	<span class="error"><?php echo $nameError; ?></span>
	<?php } ?>
    <p class="contact-form-sender">
    <input id="sender" class="required requiredField" type="text" placeholder="Name" name="sender">
    </p>
    
    <?php if($emailError != '') { ?>
	<span class="error"><?php echo $emailError;?></span>
	<?php } ?>
    <p class="contact-form-email">
    <input id="email" class="required requiredField" type="text" placeholder="Email" name="email">
    </p>
    
    <?php if($titleError != '') { ?>
	<span class="error"><?php echo $titleError;?></span>
	<?php } ?>
    <p class="contact-form-subject">
    <input id="subject" class="required requiredField" type="text" placeholder="Subject" name="subject">
    </p>
    
    <?php if($commentError != '') { ?>
	<span class="error"><?php echo $commentError;?></span>
	<?php } ?>
    <p class="contact-form-content">
    <textarea id="contact-content" class="required requiredField" placeholder="Your enquiries" name="content"></textarea>
    </p>
    
    <span class="button-met dark"><input id="consubmit" type="submit" name="consubmit" value="Send" /></span>
</form>
<?php } ?>

</div>

<div class="one-half last">
<?php if ( $data['matrix_contact_number'] != '' ) { ?>
<div class="page-sub-title">
<h1>Call Us</h1>
</div>
    <p class="hl2"><?php echo $data['matrix_contact_number']; ?></p>
<?php } ?>
</div>

</div><!-- end #pg-content -->
<!-- END PAGE CONTENT -->
</section>
<!-- END PAGE -->
</section>
<!-- END CONTENT -->

<?php get_footer(); ?>