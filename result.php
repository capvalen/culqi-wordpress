<?php
/**
 * Template for displaying quiz result.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-quiz/result.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user      = LP_Global::user();
$quiz      = LP_Global::course_item_quiz();
$quiz_data = $user->get_quiz_data( $quiz->get_id() );
$result    = $quiz_data->get_results( false );

if ( $quiz_data->is_review_questions() ) {
	return;
} ?>

<div class="quiz-results <?php echo esc_attr( $result['grade'] ); ?>">

	<h3 class="result-title"><?php _e( 'Tus resultados', 'eduma' ); ?></h3>

<!--	<div class="result-grade">-->
<!--		<div class="thim-grage">-->
<!--			<span class="result-achieved">--><?php //echo $quiz_data->get_percent_result(); ?><!--</span>-->
<!--			<span class="result-require">--><?php //echo $quiz->get_passing_grade(); ?><!--</span>-->
<!--		</div>-->
<!--	</div>-->

	<div class="result-summary">
		<div class="result-field correct">
			<span><?php echo _x( 'Correctas', 'quiz-result', 'eduma' ); ?></span>
			<span class="value"><?php echo $result['question_correct']; ?></span>
		</div>
		<div class="result-field wrong">
			<span><?php echo _x( 'Malas', 'quiz-result', 'eduma' ); ?></span>
			<span class="value"><?php echo $result['question_wrong']; ?></span>
		</div>
		<div class="result-field empty">
			<span><?php echo _x( 'Sin resp.', 'quiz-result', 'eduma' ); ?></span>
			<span class="value"><?php echo $result['question_empty']; ?></span>
		</div>
		<div class="result-field points">
			<span><?php echo _x( 'Preguntas', 'quiz-result', 'eduma' ); ?></span>
			<span class="value"><?php echo $quiz->count_questions(); ?></span>
		</div>
		<div class="result-field time">
			<span><?php esc_html_e( 'Tiempo', 'eduma' ) ?></span>
			<span class="value"><?php echo $result['time_spend']; ?></span>
		</div>
	</div>

	<?php
	if ( $result['grade'] ) {
		if ( 'point' == $quiz->get_passing_grade() ) {
			$pass_point = $quiz->get_data( 'passing_grade' );
		} else {
			$pass_point = round( $quiz->get_data( 'passing_grade' ) ) . '%';
		}

		$percent_result = $quiz_data->get_percent_result();

		if ( $result['grade'] == 'passed' ) {
			$class = 'success';
			$grade = __( 'passed', 'eduma' );
		} else {
			$class = 'error';
			$grade = __( 'failed', 'eduma' );
		}
		//learn_press_display_message( sprintf( __( 'Your quiz grade <b>%s</b>. Quiz requirement <b>%s</b>', 'eduma' ), $grade, $pass_point ), $class );
		learn_press_display_message( sprintf( __( 'Quiz <b>%s</b>. El resultado es %s (el mínimo para aprobar es %s).', 'eduma' ), $grade, $percent_result, $pass_point ), $class );
		if( $result['grade']== 'passed'){
			
			$idCliente= usuario_id();
			$curso = the_dramatist_return_post_id();
			$url_link = "https://ademperu.com/certificados/index.php?cliente=". $idCliente . "&curso=". $curso ;

			holaPeru($idCliente, $curso );
			
			learn_press_display_message( "</b></b><span class='dashicons dashicons-welcome-learn-more'></span> Bien aprobaste, ahora puedes <b><a href='{$url_link}' style='color: #8a6d3b;'><span class='dashicons dashicons-awards'></span> Descargar tu certificado aquí</a></b>" , 'warning' );
		}
	}
	?>


</div>