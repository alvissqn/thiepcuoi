@php
	use App\Helpers\Form;	
@endphp
<main class="row">
	<section class="mt-2 col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					{{ __('admin/settings/admin.setting_theme_heading_title') }}
				</h4>
			</div>
			<hr>
			<div class="card-content">
				<div class="card-body">
					@php
						$fontList="Roboto,Noto Serif TC,Noto Serif SC,Open Sans,Montserrat,Roboto Condensed,Oswald,Source Sans Pro,Merriweather,Roboto Slab,Noto Sans,Playfair Display,Roboto Mono,Lora,Muli,Fira Sans,Arimo,Nunito,Noto Serif,Inconsolata,Charmonman,Quicksand,Bungee,Cabin,Josefin Sans,Anton,Nunito Sans,Lobster,Pacifico,Varela Round,Dancing Script,Yanone Kaffeesatz,Exo,Asap,Encode Sans Condensed,Comfortaa,Kanit,Amatic SC,EB Garamond,Maven Pro,Play,Francois One,Cuprum,Rokkitt,Patrick Hand,Alegreya,Fira Sans Condensed,Vollkorn,Noto Sans SC,Old Standard TT,Saira Semi Condensed,Alegreya Sans,Cormorant Garamond,Prompt,Noticia Text,Tinos,Alfa Slab One,Playfair Display SC,KoHo,Fira Sans Extra Condensed,Cabin Condensed,VT323,Paytone One,Philosopher,IBM Plex Sans,Bangers,M PLUS 1p,Prata,Saira Extra Condensed,K2D,Taviraj,Niramit,Bai Jamjuree,Mali,Krub,Kodchasan,Fahkwang,Chakra Petch,Montserrat Alternates,Lalezar,Jura,Sigmar One,Bungee Inline,Archivo,Srisakdi,Baloo,Bevan,Alegreya Sans SC,Saira,Cormorant,Pridi,Cousine,Mitr,Spectral,IBM Plex Serif,Saira Condensed,Pangolin,Space Mono,Sawarabi Gothic,Yeseva One,Judson,Alegreya SC,Arsenal,Itim,Markazi Text,Pattaya,M PLUS Rounded 1c,Arima Madurai,IBM Plex Mono,Encode Sans,Asap Condensed,IBM Plex Sans Condensed,Maitree,Andika,Baloo Bhaina,Trirong,Lemonada,Baloo Bhaijaan,Cormorant SC,Sedgwick Ave,Athiti,Sriracha,Baloo Paaji,Patrick Hand SC,Cormorant Upright,Podkova,Faustina,Cormorant Infant,Baloo Tamma,Chonburi,David Libre,Encode Sans Semi Condensed,Bungee Shade,Encode Sans Semi Expanded,Spectral SC,Baloo Chettan,Farsan,Encode Sans Expanded,Baloo Thambi,Baloo Bhai,Coiny,Manuale,Baloo Tammudu,Cormorant Unicase,Vollkorn SC,Sedgwick Ave Display,Baloo Da,Bungee Outline,Bungee Hairline,";
						$i=0;
						$fontFamily = ['' => 'Default'];
						foreach(explode(",", rtrim($fontList,",") ) as $name) {
							$i++;
							$fontFamily[$name] = $i.". ".$name;
						}
					@endphp
					<div class="mb-2">
						{!!
							Form::select2([
								'title'         => __('admin/settings/admin.setting_theme_font'),
								'name'          => 'settings__style_font',
								'class'         => '',
								'attr'          => 'onchange="settingsSave(this, true)"',
								'icon'          => '',
								'icon_position' => 'right',
								'options'       => $fontFamily,
								//'selected'    => ['op2g2'],
								'multiple'      => false,
								'search'        => false
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::text([
								'title'         => '',
								'placeholder'   => __('admin/settings/admin.setting_theme_font_size'),
								'name'          => 'settings__style_font_size',
								//'value'         => ,
								'class'         => '',
								'attr'          => '',
								'icon'          => '',
								'icon_position' => 'right'
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::colorPicker([
								'label'         => __('admin/settings/admin.setting_theme_primary_color'),
								'name'          => 'settings__style_primary_color',
								'default'       => '#5A8DEE',
								'required'      => true,
								'class'         => '',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::colorPicker([
								'label'         => __('admin/settings/admin.setting_theme_primary_hover'),
								'name'          => 'settings__style_primary_hover',
								'default'       => '#5A8DEE',
								'required'      => true,
								'class'         => '',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::colorPicker([
								'label'         => __('admin/settings/admin.setting_theme_secondary_color'),
								'name'          => 'settings__style_secondary_color',
								'default'       => '#475F7B',
								'required'      => true,
								'class'         => '',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::colorPicker([
								'label'         => __('admin/settings/admin.setting_theme_secondary_hover'),
								'name'          => 'settings__style_secondary_hover',
								'default'       => '#506b8b',
								'required'      => true,
								'class'         => '',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::textarea([
								'title'       => __('admin/settings/admin.setting_theme_css_custom'),
								'placeholder' => 'a{color: red}',
								'name'        => 'settings__style_css_custom',
								//'value'       => '',
								'rows'        => 8,
								'class'       => '',
								'attr'        => '',
							])
						!!}
					</div>
				</div><!--/.card-body-->
			</div><!--/.card-content-->
		</div>
	</section>

	<section class="mt-2 col-md-12">
	</section>
</main>
<div class="form-footer-sticky" onclick="settingsSave(this, true)">
	<button type="button" class="btn btn-primary form-save-btn">
		<i class="bx bx-save"></i>
		{{ __('admin/settings.save_button_label') }}
	</button>
</div>