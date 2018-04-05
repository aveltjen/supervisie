<?php
/**
 * Logiciel : exemple d'utilisation de HTML2PDF
 * 
 * Convertisseur HTML => PDF, utilise fpdf de Olivier PLATHEY 
 * Distribu� sous la licence LGPL. 
 *
 * @author		Laurent MINGUET <webmaster@html2pdf.fr>
 * 
 * isset($_GET['vuehtml']) n'est pas obligatoire
 * il permet juste d'afficher le r�sultat au format HTML
 * si le param�tre 'vuehtml' est pass� en param�tre _GET
 */
 	// r�cup�ration du contenu HTML
 	ob_start();
?>
<style type="text/css">
<!--
.div { background: #CCDDCC; color: #002200; text-align: center; width: 70mm; height: 20mm; margin: 2mm;  }
.div1 { border: solid 2mm black; border-radius: 5mm; 				-moz-border-radius: 5mm;				}
.div2 { border: solid 2mm black; border-radius: 3mm 10mm 0mm 3mm; 	-moz-border-radius: 3mm 10mm 0mm 3mm;	}
.div3 { border: solid 2mm black; border-radius: 10mm / 7mm; 		-moz-border-radius: 10mm / 7mm;			}
.div4 { border: solid 6mm black; border-radius: 5mm / 10mm;			-moz-border-radius: 5mm / 10mm;			}
.div5 { border: solid 5mm black; border-top: none; border-bottom: none; border-radius: 5mm;			-moz-border-radius: 5mm;			}
.div6 { border: solid 5mm black; border-left: none; border-right: none; border-radius: 5mm;			-moz-border-radius: 5mm;			}
.div7 { border: solid 5mm black; border-left: none; border-top: none; border-radius: 5mm;			-moz-border-radius: 5mm;			}
.div8 { border-radius: 8mm; -moz-border-radius: 8mm; border-left: solid 2mm #660000; border-top: solid 1mm #006600; border-right: solid 2mm #000066; border-bottom: solid 4mm #004444;}
-->
</style>
<page> 
	<div class="div div1">Exemple de div</div>
	<div class="div div2">Exemple de div</div>
	<div class="div div3">Exemple de div</div>
	<div class="div div4">Exemple de div</div>
	<div class="div div5">Exemple de div</div>
	<div class="div div6">Exemple de div</div>
	<div class="div div7">Exemple de div</div>
	<div class="div div8">Exemple de div</div>
</page>
<?php
 	$content = ob_get_clean();
	
	// conversion HTML => PDF
	require_once(dirname(__FILE__).'/../html2pdf.class.php');
	$html2pdf = new HTML2PDF('P','A4','fr', array(5, 5, 5, 5));
	$html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
	$html2pdf->Output('radius.pdf');
