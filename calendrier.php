<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Calendrier</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<style type="text/css">
			.table > tbody > tr > th {
				text-align:center;
			}
			.table > tbody > tr > td.size {
				min-width: 14%;
				max-width: 15%;
			}
			.table > tbody > tr > td > h4 {
				text-align:right;
			}
			.container-fluid .row button {
				margin-bottom: 2px;
			}
		</style>
	</head>
	<body>
		<?php
			//initialise les jours et les mois
			// create array with days and months (in french)
			$base = array();
			$base['months'] = array(1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Aout', 9 => 	'	Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Decembre');
			$base['days'] = array(1 => 'Lu', 2 => 'Ma', 3 => 'Me', 4 => 'Je', 5 => 'Ve', 6 => 'Sa', 7 => 'Di');

			//initialise quelques évenements
			// create somes events
			$events = array();
			$events[0] = array('date_begin' => date('Y-m-d', strtotime('-2 year')), 'date_end' => date('Y-m-d', strtotime('+1 year')), 'event' => 'studies', 'important' => 'hight');
			$events[1] = array('date_begin' => date("Y-m-d"), 'date_end' => date("Y-m-d"), 'event' => 'personal', 'interview' => 'medium');
			$events[2] = array('date_begin' => date("Y-m-d"), 'date_end' => date("Y-m-d"), 'event' => 'personal', 'work appointment' => 'hight');
			$events[3] = array('date_begin' => date("Y-m-d", strtotime('+3 day')), 'date_end' => date("Y-m-d", strtotime('+3 day')), 'event' => 'personal appointment', 'important' => 'low');
			$events[4] = array('date_begin' => date('Y-m-d', strtotime('-37 day')), 'date_end' => date('Y-m-d', strtotime('-4 day')), 'event' => 'travel', 'important' => 'low');
			$events[5] = array('date_begin' => date('Y-m-d', strtotime('-3 day')), 'date_end' => date('Y-m-d', strtotime('+3 day')), 'event' => 'Work on big project', 'important' => 'medium');

			$intervalYear = 3;

			//récupère les dates actuelles
			// catch actualy dates
			$dayNow = date("j");
			$monthNow = date("n");
			$yearNow = date("Y");
			
			//récupération du mois et de l'année envoyés en POST
			// take months and years send in POST
			if (isset($_POST['year'])) {
				$yearN = $_POST['year'];
			}
			else {
				$yearN = $yearNow;
			}
			if (isset($_POST['month'])) {
				if ($_POST['month'] < 1) {
					$_POST['month'] = 12;
					$yearN = $yearN - 1;
					$_POST['year'] = $yearN;
				}
				if ($_POST['month'] > 12) {
					$_POST['month'] = 1;
					$yearN = $yearN + 1;
					$_POST['year'] = $yearN;
				}
				$monthN = $_POST['month'];
			}
			else {
				$monthN = $monthNow;
			}
			
			//nombre de jours dans le mois et numero du premier jour du mois
			// take days in one month and first day of the month
			$nbrDayInMonth = date("t", mktime(0,0,0,$monthN,1,$yearN));
			$firstDay = date("w", mktime(0,0,0,$monthN,1,$yearN));
			//ajustement du jour (si =0 (dimanche), alors =7)
			// set first day (if =0 (sunday), then =7)
			if ($firstDay == 0) {
				$firstDay = 7;
			}
			
			//nbr de jours du moi d'avant
			// days of the previous month
			if ($monthN - 1 < 1) {
				$m = 12;
			}
			else {
				$m = $monthN - 1;
			}
			$preDays = date("t", mktime(0,0,0,$m,1,$yearN));
			//nbr de jours du mois d'apres
			// days of the month after
			if ($monthN + 1 > 12) {
				$m = 1;
			}
			else {
				$m = $monthN + 1;
			}
			$aftMonth = date("t", mktime(0,0,0,$m,1,$yearN));
			
			$tab_cal = array(array(),array(),array(),array(),array(),array(),array());
			$t = 1;
			$style = "";
			for($i=1; $i<7; $i++) {
				for($j=1; $j<8; $j++) {
					//on met les jours du mois précédent
					// we set days of previous month
					if ($t == 1 && $j < $firstDay) {
						$style = "color:#aaa;";
						$day = $preDays-($firstDay-($j))+1;
						$tab_cal[$i][$j] = "<div style='{$style}'>{$day}</div>";
					}
					//on met le premier jour du mois à afficher
					// we set the first day of the month to print
					elseif ($j == $firstDay && $t == 1) {
						$style = "color:#000;";
						$tab_cal[$i][$j] = "<div style='{$style}'>{$t}</div>";
						$t++;
					}
					//on met le premier jour du mois d'après
					// we set the first day of the next month
					elseif ($t > $nbrDayInMonth) {
						$style = "color:#aaa;";
						$tab_cal[$i][$j] = "<div style='{$style}'>1</div>";
						$t = 2;
					}
					//on met les jours suivants du mois à afficher et du mois suivant
					// we set nexts days os the month to print and the next month
					elseif ($t > 1 && $t <= $nbrDayInMonth) {
						$tab_cal[$i][$j] = "<div style='{$style}'>{$t}</div>";
						$t++;
					}
				}
			}
		?>
		
		<div class="container">
			<form method="POST" class="form-inline" action="" id="search_year">
				<div class="form-group">
					<label for="year">Année :</label>
					<select class="form-control" name="year" id="year">
						<?php for ($i=0; $i < (($intervalYear) * 2 + 1); $i++) {
							$year = $yearNow - $intervalYear + $i;
							if ($year == $yearN) {
							 	$default = 'selected';
							}
							else {
								$default = '';
							} ?>
							<option value='<?=$year?>' <?=$default?>><?=$year?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label for="month">Mois :</label>
					<select class="form-control" name="month" id="month">
						<?php foreach ($base['months'] as $key => $month) {
							if ($key == $monthN) {
							 	$default = 'selected';
							}
							else {
								$default = '';
							} ?>
							<option value='<?=$key?>' <?=$default?>><?=$month?></option>
						<?php } ?>
					</select>
				</div>
				<button type="submit" class="btn btn-default">Voir</button>
			</form>
			<!-- Tableau du calendrier -->
			<div class="thumbnail">
				<table class="table table-bordered">
					<caption><h1><span class="glyphicon glyphicon-calendar"></span> Calendrier :</h1></caption>
					<!-- Année -->
					<tr>
						<th colspan="7">
							<h2>
								<!-- Si l'utilisateur cherche à voir plus loin que trois ans en arrière, désactive le bouton -->
								<?php if ($yearN <= $yearNow - $intervalYear) { ?>
									<button class="btn btn-primary btn-sm pull-left" role="button" disabled="disabled" href="#">
										<span class="glyphicon glyphicon-backward"></span>
									</button>
								<?php } else { ?>
									<form action="" method="POST" class="visible-lg-inline pull-left">
										<?php if (isset($_POST['month'])): ?>
											<input name="month" type="hidden" value="<?=$_POST['month']?>" id="">
										<?php endif ?>
										<button name="year" value="<?=$yearN-1?>" type="submit" class="btn btn-primary btn-sm">
											<span class="glyphicon glyphicon-backward"></span>
										</button>
									</form>
								<?php } ?>
								<!--  -->
								<?=$yearN?>
								<!-- Si l'utilisateur cherche à voir plus loin que trois ans en avant, désactive le bouton -->
								<?php if ($yearN >= $yearNow + $intervalYear) { ?>
									<button class="btn btn-primary btn-sm pull-right" role="button" disabled="disabled">
										<span class="glyphicon glyphicon-forward"></span>
									</button>
								<?php } else { ?>
									<form action="" method="POST" class="visible-lg-inline pull-right">
										<?php if (isset($_POST['month'])): ?>
											<input name="month" type="hidden" value="<?=$_POST['month']?>" id="">
										<?php endif ?>
										<button name="year" value="<?=$yearN+1?>" type="submit" class="btn btn-primary btn-sm">
											<span class="glyphicon glyphicon-forward"></span>
										</button>
									</form>
								<?php } ?>
							</h2>
						</th>
					</tr>
					<tr>
						<th colspan="7">
							<h3>
								<?php if ($yearN <= $yearNow - 3 && $monthN == 1) { ?>
									<button class="btn btn-primary btn-xs pull-left" role="button" disabled="disabled" href="#">
										<span class="glyphicon glyphicon-backward"></span>
									</button>
								<?php } else { ?>
									<form action="" method="POST" class="visible-lg-inline pull-left">
										<?php if (isset($_POST['year'])): ?>
											<input name="year" type="hidden" value="<?=$_POST['year']?>" id="">
										<?php endif ?>
										<button name="month" value="<?=$monthN-1?>" type="submit" class="btn btn-primary btn-xs">
											<span class="glyphicon glyphicon-backward"></span>
										</button>
									</form>
								<?php } ?>
								<?=$base['months'][$monthN]?>
								<?php if ($yearN >= $yearNow + 3 && $monthN == 12) { ?>
									<button class="btn btn-primary btn-xs pull-right" role="button" disabled="disabled">
										<span class="glyphicon glyphicon-forward"></span>
									</button>
								<?php } else { ?>
									<form action="" method="POST" class="visible-lg-inline pull-right">
										<?php if (isset($_POST['year'])): ?>
											<input name="year" type="hidden" value="<?=$_POST['year']?>" id="">
										<?php endif ?>
										<button name="month" value="<?=$monthN+1?>" type="submit" class="btn btn-primary btn-xs">
											<span class="glyphicon glyphicon-forward"></span>
										</button>
									</form>
								<?php } ?>
							</h3>
						</th>
					</tr>
					<tr>
						<?php
							foreach ($base['days'] as $d => $day) { ?>
								<th>
									<?=$day?>
								</th>
							<?php }
						?>
					</tr>
					<?php
						for($i=1; $i<7; $i++) { ?>
							<tr>
							<?php for($j=1; $j<8; $j++) {
								//récupérer le jour dans la chaine de charactère retournée
								// catch the day in the string return
								preg_match_all('!\d+!', $tab_cal[$i][$j], $day);
								if (isset($day[0][1])) {
									$day = $day[0][1];
								}
								else {
									$day = 0;
								}
								if ($monthN == $monthNow && $yearN == $yearNow && $day == $dayNow) {
									$bground = 'info';
								} else {
									$bground = '';
								}?>
								<td  class="<?=$bground?> size">
									<h4>
										<?=$tab_cal[$i][$j]?>
									</h4>
									<?php foreach ($events as $e) {
										if ($e['date_begin'] != $e['date_end']) {
											$dateEventBegin = explode("-", $e['date_begin']);
											$dateEventYearBegin = $dateEventBegin[0];
											$dateEventMonthBegin = $dateEventBegin[1];
											$dateEventMonthBegin = $dateEventMonthBegin + 0; //enlève le 0 devant
											$dateEventDayBegin = $dateEventBegin[2];
											$dateEventDayBegin = $dateEventDayBegin + 0;
						
											$dateEventEnd = explode("-", $e['date_end']);
											$dateEventYearEnd = $dateEventEnd[0];
											$dateEventMonthEnd = $dateEventEnd[1];
											$dateEventMonthEnd = $dateEventMonthEnd + 0; //enlève le 0 devant
											$dateEventDayEnd = $dateEventEnd[2];
											$dateEventDayEnd = $dateEventDayEnd + 0;
	
											if ($day <> null) {
												//change la couleur
												// change color
												if ($e['important'] == 'hight') {
													$col = "danger";
												} elseif ($e['important'] == 'medium') {
													$col = "warning";
												} elseif ($e['important'] == 'low') {
													$col = "success";
												}
												if ($yearN > $dateEventYearBegin && $yearN < $dateEventYearEnd) { ?>
													<div class="container-fluid">
														<div class="row">
															<button type="button" class="btn btn-<?=$col?> btn-sm btn-block" data-toggle="tooltip" data-placement="top" title="<?=$e['event']?> du <?=$e['date_begin']?> au <?=$e['date_end']?>">
															</button>
														</div>
													</div>
												<?php }
												elseif ($dateEventYearBegin == $dateEventYearEnd && $yearN == $dateEventYearEnd) {
													if ($monthN > $dateEventMonthBegin && $monthN < $dateEventMonthEnd) { ?>
														<div class="container-fluid">
															<div class="row">
																<button type="button" class="btn btn-<?=$col?> btn-sm btn-block" data-toggle="tooltip" data-placement="top" title="<?=$e['event']?> du <?=$e['date_begin']?> au <?=$e['date_end']?>">
																</button>
															</div>
														</div>
													<?php }
													elseif ($dateEventMonthBegin == $dateEventMonthEnd && $monthN == $dateEventMonthBegin) {
														if ($day >= $dateEventDayBegin && $day <= $dateEventDayEnd) { ?>
															<div class="container-fluid">
																<div class="row">
																	<button type="button" class="btn btn-<?=$col?> btn-sm btn-block" data-toggle="tooltip" data-placement="top" title="<?=$e['event']?> du <?=$e['date_begin']?> au <?=$e['date_end']?>">
																	</button>
																</div>
															</div>
														<?php }
													}
													elseif ($monthN == $dateEventMonthBegin && $monthN < $dateEventMonthEnd) {
														if ($day >= $dateEventDayBegin) { ?>
															<div class="container-fluid">
																<div class="row">
																	<button type="button" class="btn btn-<?=$col?> btn-sm btn-block" data-toggle="tooltip" data-placement="top" title="<?=$e['event']?> du <?=$e['date_begin']?> au <?=$e['date_end']?>">
																	</button>
																</div>
															</div>
														<?php }
													}
													elseif ($monthN == $dateEventMonthEnd && $monthN > $dateEventMonthBegin) {
														if ($day <= $dateEventDayEnd) { ?>
															<div class="container-fluid">
																<div class="row">
																	<button type="button" class="btn btn-<?=$col?> btn-sm btn-block" data-toggle="tooltip" data-placement="top" title="<?=$e['event']?> du <?=$e['date_begin']?> au <?=$e['date_end']?>">
																	</button>
																</div>
															</div>
														<?php }
													}
												}
												elseif ($yearN == $dateEventYearBegin && $yearN < $dateEventYearEnd) {
													if ($monthN > $dateEventMonthBegin) { ?>
														<div class="container-fluid">
															<div class="row">
																<button type="button" class="btn btn-<?=$col?> btn-sm btn-block" data-toggle="tooltip" data-placement="top" title="<?=$e['event']?> du <?=$e['date_begin']?> au <?=$e['date_end']?>">
																</button>
															</div>
														</div>
													<?php }
													elseif ($monthN == $dateEventMonthBegin) {
														if ($day >= $dateEventDayBegin) { ?>
															<div class="container-fluid">
																<div class="row">
																	<button type="button" class="btn btn-<?=$col?> btn-sm btn-block" data-toggle="tooltip" data-placement="top" title="<?=$e['event']?> du <?=$e['date_begin']?> au <?=$e['date_end']?>">
																	</button>
																</div>
															</div>
														<?php }
													}
												}
												elseif ($yearN == $dateEventYearEnd && $yearN > $dateEventYearBegin) {
													if ($monthN < $dateEventMonthEnd) { ?>
														<div class="container-fluid">
															<div class="row">
																<button type="button" class="btn btn-<?=$col?> btn-sm btn-block" data-toggle="tooltip" data-placement="top" title="<?=$e['event']?> du <?=$e['date_begin']?> au <?=$e['date_end']?>">
																</button>
															</div>
														</div>
													<?php }
													elseif ($monthN == $dateEventMonthEnd) {
														if ($day <= $dateEventDayEnd) { ?>
															<div class="container-fluid">
																<div class="row">
																	<button type="button" class="btn btn-<?=$col?> btn-sm btn-block" data-toggle="tooltip" data-placement="top" title="<?=$e['event']?> du <?=$e['date_begin']?> au <?=$e['date_end']?>">
																	</button>
																</div>
															</div>
														<?php }
													}
												}
											}
										}
									} ?>
									<?php foreach ($events as $e) {
										if ($e['date_begin'] == $e['date_end']) {
											$dateEvent = explode("-", $e['date_begin']);
											$dateEventYear = $dateEvent[0];
											$dateEventMonth = $dateEvent[1];
											$dateEventMonth = $dateEventMonth + 0; //enlève le 0 devant
											$dateEventDay = $dateEvent[2];
											$dateEventDay = $dateEventDay + 0;
				
											if ($yearN == $dateEventYear && $monthN == $dateEventMonth && $day == $dateEventDay) {
												if ($e['important'] == 'hight') {
													$col = "danger";
												} elseif ($e['important'] == 'medium') {
													$col = "warning";
												} elseif ($e['important'] == 'low') {
													$col = "success";
												} ?>
												<button type="button" class="btn btn-<?=$col?> btn-sm" data-toggle="tooltip" data-placement="top" title="<?=$e['event']?>">
												</button>
											<?php }
										}
									} ?>
								</td>
							<?php } ?>
							</tr>
						<?php }
					?>
				</table>
			</div> <!-- table -->
		</div><!-- container -->

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script type="text/javascript">
		$(function () {
			$('[data-toggle="tooltip"]').tooltip()
		})
		</script>
	</body>
</html>