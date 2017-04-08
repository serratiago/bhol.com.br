<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Magazine_Plus
 */

?>
<?php if (in_category('trem-bao')) : ?> 
	<div id="div_chamada_cpg"> 
  		<div id="div_fechar"><a href="#" id="fecha_cpg">[X]</a></div>
		Várias coisas boas acontecem em BH, e que tal tornar público?<br />
        Viu algo legal e quer compartilhar? <br />
        Tem alguma boa história para contar? <br />
        Então, conta pra gente!<br />
        <a href="http://bhol.com.br/trem-bao/">
		<img src="http://bhol.com.br/wp-content/uploads/2017/02/bhol_botao_trem_bão.png">
        </a>    
        <br/>

	</div>
<?php endif; ?>
<?php if (in_category('venda-seu-peixe')) : ?> 
	<div id="div_chamada_cpg"> 
    <div id="div_fechar"><a href="#" id="fecha_cpg">[X]</a></div>
		Espaço para você falar do seu trabalho! <br />
        Venda seu peixe e faça sua propaganda aqui! <br />
        Mande release sobre seu projeto, empresa, atividade ou comércio local.<br />
        Então, conta pra gente!<br />
        <a href="http://bhol.com.br/venda-seu-peixe/">
		<img src="http://bhol.com.br/wp-content/uploads/2017/02/bhol_botao_venda_seu_peixe.png">
        </a>
         <br/>
	</div>
   
<?php endif; ?>
<?php if (in_category('vish')) : ?> 
	<div id="div_chamada_cpg"> 
    <div id="div_fechar"><a href="#" id="fecha_cpg">[X]</a></div>
		É, assim como acontecem as coisas boas, também acontecem coisas não tão legais assim. <br />
        Alguma situação ruim e você quer contar para alguem?!<br />
        Então, conta pra gente!<br />
        Não daremos soluções ao problema, mas, serviremos como espaço de desabafo.<br />
        <a href="http://bhol.com.br/vish/">
		<img src="http://bhol.com.br/wp-content/uploads/2017/02/bhol_botao_vish.png">
        </a>
        <br/>
	</div>
    
<?php endif; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta">
			<?php magazine_plus_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

    <?php
	  /**
	   * Hook - magazine_plus_single_image.
	   *
	   * @hooked magazine_plus_add_image_in_single_display -  10
	   */
	  //do_action( 'magazine_plus_single_image' );
	?>

	<div class="entry-content-wrapper">
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'magazine-plus' ),
					'after'  => '</div>',
				) );
			?>

<fieldset id="fdt_servico">
    <legend class="lgd_servico">Serviço</legend>
<?
$custom_field_keys = get_post_custom_keys();
foreach ( $custom_field_keys as $key => $value ) {
	 $valuet = trim($value);
    if ( '_' == $valuet{0} )
        continue;
 		$mykey_values = get_post_custom_values($value);
?>
<div class="div_meta_int">
   <h1 class="tit_info"><?= $value ?></h1>
  <?= nl2br($mykey_values[0])?>
</div>
<?
}
?>
 </fieldset>
		</div><!-- .entry-content -->
	</div><!-- .entry-content-wrapper -->

	<footer class="entry-footer">
		<?php magazine_plus_entry_footer(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
