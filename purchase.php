<?php
/**
 * Template for displaying Purchase button in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/buttons/purchase.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! isset( $course ) ) {
	$course = learn_press_get_course();
}
$guest_checkout = ( LP()->checkout()->is_enable_guest_checkout() ) ? 'guest_checkout' : '';

$checkout_redirect = add_query_arg( 'enroll-course', $course->get_id(), $course->get_permalink() );
$login_redirect    = add_query_arg( 'redirect_to', $checkout_redirect, thim_get_login_page_url() );
?>

<div class="lp-course-buttons">

	<?php do_action( 'learn-press/before-purchase-form' ); ?>

	<form name="purchase-course" class="purchase-course form-purchase-course <?php echo $guest_checkout; ?>" method="post" enctype="multipart/form-data">

		<?php do_action( 'learn-press/before-purchase-button' ); ?>

		<input type="hidden" name="purchase-course" value="<?php echo esc_attr( $course->get_id() ); ?>" />
		<input type="hidden" name="purchase-course-nonce"
		       value="<?php echo esc_attr( LP_Nonce_Helper::create_course( 'purchase' ) ); ?>" />

		
		<?php
		
		if(wp_get_current_user()->ID==0){
			?> <!-- <div class="course-wishlist-box" ><span style="background: black; padding: 5px 15px; color:white!important">Crea o entra con una cuenta antes de comprar</span></div>  -->
		<a href="https://ademperu.com/formulario-cuenta/">
		<div class="course-wishlist-box" style="background: black; padding: 5px 15px;"><span><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>REGÍSTRESE PARA COMPRAR</span> </span></div> 
		</a>
		<?php 
		}else{
			$destino = get_the_ID();
		?>
			<a href="https://ademperu.com/checkout-curso/compra.php?destino=<?= $destino; ?>&cliente=<?= wp_get_current_user()->ID; ?>">
			<div class="course-wishlist-box" style="background: black; padding: 5px 15px;">
			<span><i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>COMPRAR CURSO</span> </span>
			</div>
			</a>
		<?php
		}
		
		?>
		
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( $login_redirect ); ?>">

		<?php do_action( 'learn-press/after-purchase-button' ); ?>

	</form>

	<?php do_action( 'learn-press/after-purchase-form' ); ?>

</div>

