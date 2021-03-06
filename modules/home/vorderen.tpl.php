<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
<head>
<title>Supervisor V3</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link href="styles/styleSV.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript">

// Centered Pop-Up Window (v1.0)
// (C) 2002 www.smileycat.com
// Free for all users, but leave in this header

var win = null;
function newWindow(mypage,myname,w,h,features) {
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  if (winl < 0) winl = 0;
  if (wint < 0) wint = 0;
  var settings = 'height=' + h + ',';
  settings += 'width=' + w + ',';
  settings += 'top=' + wint + ',';
  settings += 'left=' + winl + ',';
  settings += features;
  win = window.open(mypage,myname,settings);
  win.window.focus();
}

</script>
<style type="text/css" media="all">
@import "jquery/jquery-tooltip/css/global.css";
</style>
			<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" media="all" />
			<link rel="stylesheet" href="jquery/css/ui-lightness/jquery-ui-1.8.18.custom.css" type="text/css" media="all" />
			<link rel="stylesheet" href="jquery/development-bundle/themes/ui-lightness/jquery.ui.tooltip.css" type="text/css" media="all" />
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
			<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>
			<script src="jquery/jquery-tooltip/js/jtip.js" type="text/javascript"></script>
			
<script>
	$(function() {
			var pickerOpts = {dateFormat:"yy-mm-dd"}; 
					    $("#date").datepicker(pickerOpts);

		});
		
	
	$(function(){        
	        $('#mySubmitBtn').click(function() {
		           $( "#dialog-confirm" ).dialog({
							resizable: false,
							height:140,
							modal: true,
							buttons: {
								"Bevestigen": function() {
									$( this ).dialog( "close" );
									$('#addvordering').submit();
								},
								Annuleren: function() {
									$( this ).dialog( "close" );
								}
							}
						});
		        });
	});
	
	$(function(){        
	        
		$('#mySubmitBtn2').click(function()
		{ 
			$('#addvordering').submit();
		});
				
	});
	
	$(document).ready(function(){

	        $(".slidingDiv").hide();
	        $(".show_hide").show();

	    $('.show_hide').click(function(){
	    $(".slidingDiv").slideToggle();
	    });

	});
	   
</script>

</head>
<body>
{titel}


<div id="dialog-confirm" title="Gelinkte posten gevonden!" style="display: none;">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Let op: De vordering wordt ook bij de gelinkte posten doorgevoerd.</p>
</div>
<table cellpadding="0" cellspacing="0" border="0" align="center">
  <tr>
    
    <td rowspan="3" valign="top" align="center" align="left">

    <table>
    	<tr>
    		<td>
    			<div align="center">
    				
    	<table width="740" border="0" cellpadding="0" cellspacing="0" class="tekstkader" align="left">
				<!-- fwtable fwsrc="Untitled" fwbase="index.png" fwstyle="Dreamweaver" fwdocid = "234834546" fwnested="0" -->
				  <tr>
				   <td height="29" background="images/index_r1_c1.gif"></td>
				   <td width="317" background="images/index_r1_c2.gif"></td>
				   <td width="8" background="images/index_r1_c3.png"></td>
				   <td width="91" background="images/index_r1_c4.png" align="center" valign="middle"><b>Vorderen</b></td>
				   <td width="9" background="images/index_r1_c5.png"></td>
				   <td width="320" background="images/index_r1_c6.gif">&nbsp;</td>
				   <td background="images/index_r1_c7.gif"></td>
		
				  </tr>
				  <tr>
				   <td background="images/index_r2_c1.png"><img src="images/spacer.gif" height="3" alt="" width="13"></td>
				   <td colspan="5" bgcolor="White" align="center" valign="top">
						<table>
                        	<tr>
                            	<td valign="top">
                            	{txt_delete}
                            	{txt_wijzig}
                            	<table cellspacing="0" width="100%" class="tekstnormal">
                            		<tr style="background-color:#EFEFEF;">
                            			<td width="333px" align="center">{vorigepost}</td>
                            			<td><span class="formInfo"><a href="jquery/jquery-tooltip/bladeren.htm?width=280" style="color:white;" class="jTip" id="one" name="Naar volgende / vorige post">?</a></span></td>
                            			<td width="343px" align="center">{volgendepost}</td>
                            		</tr>
                            	</table>	
                            		<fieldset>
                            			<legend class="tekstlegend"><img src="images/book-open-bookmark.png"> Gegevens geselecteerde post</legend>
										<form id="addvordering" action="?werf={werf}&msID={msID}&action=add" method="post">
                            			<table width="650" class="tekstnormal">
                            				<tr align="left">
                            					<td width="74"><b>Nummer:</b></td>
                            					<td width="63">{nummer}</td>
                            					<td width="56"></td>
                            					<td width="185">&nbsp;</td>
                            					<td width="71"><strong>Eenheden:</strong></td>
                            					<td width="72">{eenheden}</td>
                            					<td width="27"><strong>HV:</strong></td>
                            					<td width="86">{hoeveelheid}</td>
                           					</tr>
                           					<tr align="left">
                           						<td colspan="8"><b>Omschrijving:</b> {omschrijving}</td>
                           					</tr>
											<tr>
												<td colspan="8"></td>
											</tr>
 											<tr>
												<td colspan="8">
													<table border="0" class="tip">
														<tr>
															<td width="200"><img src="images/chain--arrow.png">&nbsp;<b>gelinkte postnummer(s)<span class="formInfo"><a href="jquery/jquery-tooltip/linken2.htm?width=280" class="jTip" id="one" name="Gelinkte posten">?</a></span>&nbsp;:</b></td>
														<!-- BEGIN links -->
															{links}
														<!-- END links -->
															{norecords}
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td colspan="8">
													<a href="#" class="show_hide">Toon/Verberg</a> (info gelinkte posten)
													<div class="slidingDiv">
													<table celspacing="1">
													<tr bgcolor="#e6e6e6"><td><b>Postnr.</b></td><td><b>Omschrijving</b></td></tr>
													{meerinfo}
													</table>
													
													</div>
												</td>
											</tr>
                            			</table>
                            		</fieldset><br>
                            		
                            		<fieldset>
                            		<legend class="tekstlegend"><img src="images/plus-circle-frame.png"> Nieuwe vordering ingeven</legend>
                            		
                            		
                            		
                            		<table width="650" class="tekstnormal">
     
                            			<tr align="left" class="tip">
                            				<td>Datum:</td>
                            				<td><input type="text" size="15" id="date" name="datum" value="{lastdate}"/>&nbsp;<span class="formInfo"><a href="jquery/jquery-tooltip/datum.htm?width=250" class="jTip" id="tree" name="Datum vordering invoeren">?</a></span>&nbsp;</td>
                            				<td align="right"><a href="?lastvordering=1&msID={msID}&werf={werf}"><img src="images/calendar_add.png"></a><span class="formInfo"><a href="jquery/jquery-tooltip/history.htm?width=280" class="jTip" id="two" name="Laatste vordering oproepen">?</a></span>&nbsp;<button id="{save_btn}" type="button">opslaan</button></td>
                            			</tr>
                            			<tr align="left">
                            				<td>Omschrijving:</td>
                                            <td><input type="text" size="50" name="omschrijving" value="{lastomschrijving}" /></td>
                                            <td></td>
                            			</tr>
                            			<tr align="left">
                            				<td>Uitgevoerd:</td>
                                            <td><input type="text" size="10" name="uitgevoerd"  value="{lastuitgevoerd}"/></td>
                                            <td></td>
                            			</tr>
                            		</table>
                            		
                            		
                            		</form>
                            		</fieldset>
                            		<br>
                            		
                            		<fieldset>
                            		<legend class="tekstlegend"><img src="images/property-blue.png"> Gevorderde hoeveelheden <i>(Totaal uitgevoerd: {totaal})</i></legend>
                            		<form id="addopmeting" action="?werf={werf}&msID={msID}&action=add" method="post">
                            		<table height="180">
                            			<tr>
                            				<td valign="top">
                            			<div id="vorderlistkop">	
                            			<table width="625" border="0" cellpadding="2" cellspacing="1">
                            			<tr class="tekstnormal" bgcolor="#EFEFEF" align="left">
											<td width="20"></td>
											<td width="80"><b>ID</b></td>
											<td width="80"><b>Datum</b></td>
											<td width="248"><b>Omschrijving</b></td>
											<td width="88"><b>Uitgevoerd</b></td>
											<td width="25">&nbsp;</td>
											<td width="25">&nbsp;</td>
											<td width="25">&nbsp;</td>
										</tr>	
										</table>
										</div>
										<div id="vorderlist">
										<table width="625" border="0" cellpadding="2" cellspacing="1">
                            			<!-- BEGIN vorderingen -->
                            			<tr class="drukrows" align="left">
											<td width="20">{icon}</td>
											<td width="80">{id}</td>
											<td width="80">{datum}</td>
											<td width="248">{omschrijving_vordering}</td>
											<td width="88">{uitgevoerd}</td>
											<td width="25" align="center">{delete}</td>
											<td width="25" align="center">{wijzig}</td>
											<td width="25" align="center">{opmeten}</td>
										</tr>	
                               			<!-- END vorderingen -->
                               			{geenvord}
                            			</table>
                            			</div>	
                            				</td>
                            			</tr>
                            		</table>
                            		</form>
                            		</fieldset>
                            	</td>
                            </tr>
                        </table>
				  </td>
				   <td background="images/index_r2_c7.gif">
				   </td>
				   
				  </tr>
				  <tr>
				   <td width="13" background="images/index_r3_c1.gif">&nbsp;</td>
				   <td colspan="5" background="images/index_r3_c2.gif"></td>
				   <td width="11" background="images/index_r3_c7.gif">&nbsp;</td>
				   
				  </tr>
				</table>

</body>
</html>

