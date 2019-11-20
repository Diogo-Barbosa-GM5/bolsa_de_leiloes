<?


	/* FOOTER */

		$html_footer = '<div>';

			$html_footer .= '<ul class="abas"> ';
			$html_footer .= '</ul> ';

			if(LUGAR == 'admin')
				$html_footer .= date('Y').' Administração do Site ';
			else
				$html_footer .= date('Y').' Área do '.ucfirst(LUGAR);

			$html_footer .= '<div class="seta"> ';
				$html_footer .= '<i class="icon-arrow-up"></i> ';
			$html_footer .= '</div> ';

		$html_footer .= '</div> ';

	/* FOOTER */


?>