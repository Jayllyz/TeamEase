<?php
session_start();
ob_start();
require_once 'db.php';

$idClient = $_SESSION['siret'];
$idReservation = htmlspecialchars($_GET['id']);

$req = $db->prepare('SELECT * FROM COMPANY WHERE siret = :siret');
$req->execute([
  'siret' => $idClient,
]);
$selectClient = $req->fetch(PDO::FETCH_ASSOC);

$nameClient = $selectClient['companyName'];
$mailClient = $selectClient['email'];
$addressClient = $selectClient['address'];

$req = $db->prepare('SELECT * FROM RESERVATION WHERE id = :id');
$req->execute([
  'id' => $idReservation,
]);
$selectReservation = $req->fetch(PDO::FETCH_ASSOC);

$req = $db->prepare('SELECT amount FROM ESTIMATE WHERE id_reservation = :id_reservation');
$req->execute([
  'id_reservation' => $idReservation,
]);
$selectEstimate = $req->fetch(PDO::FETCH_ASSOC);

$price = $selectEstimate['amount'];
$idActivity = $selectReservation['id_activity'];
$attendee = $selectReservation['attendee'];

$req = $db->prepare('SELECT * FROM ACTIVITY WHERE id = :id');
$req->execute([
  'id' => $idActivity,
]);
$selectActivity = $req->fetch(PDO::FETCH_ASSOC);

$activityName = $selectActivity['name'];
$activityDescription = $selectActivity['description'];
$priceAttendee = $selectActivity['priceAttendee'];

require_once '/home/php/src/vendor/autoload.php';


use Spipu\Html2Pdf\Html2Pdf;

$teamease = [
  'id' => 1,
  'siret' => '735 792 677 62909',
  'name' => 'Together&Stronger',
  'email' => 'teameasepa@gmail.com',
  'portable' => '07.12.42.69.22',
  'address' => "242 Faubourg Saint-Antoine\n75012 Paris",
];

$client = [
  'id' => $idClient,
  'name' => $nameClient,
  'mail' => $mailClient,
  'address' => $addressClient,
];

$project = [
  'id' => 1,
  'name' => $activityName,
  'status' => 1,
  'infos' => $activityDescription,
  'created' => 1,
  'paid' => false,
  'client_id' => $idClient,
  'user_id' => 1,
];

$tasks[] = [
  'id' => 1,
  'ref' => $idActivity,
  'name' => $activityName,
  'price' => $price,
  'priceAttendee' => $priceAttendee,
  'attendee' => $attendee,
  'project_id' => 1,
];

ob_start();
$total = 0;
$total_tva = 0;
?>

<style type="text/css">
	table {
		width: 100%;
		color: #717375;
		font-family: helvetica;
		line-height: 5mm;
		border-collapse: collapse;
	}
	h2  { margin: 0; padding: 0; }
	p { margin: 5px; }

	.border th {
		border: 1px solid #000;
		color: white;
		background: #000;
		padding: 5px;
		font-weight: normal;
		font-size: 14px;
		text-align: center; }
	.border td {
		border: 1px solid #CFD1D2;
		padding: 5px 10px;
		text-align: center;
	}
	.no-border {
		border-right: 1px solid #CFD1D2;
		border-left: none;
		border-top: none;
		border-bottom: none;
	}
	.space { padding-top: 250px; }

	.10p { width: 10%; } .15p { width: 15%; } 
    .25p { width: 25%; } .50p { width: 50%; } 
    .60p { width: 60%; } .75p { width: 75%; }
</style>

<page backtop="10mm" backleft="10mm" backright="10mm" backbottom="10mm" footer="page;">

	<page_footer>
		<hr />
		<p>Fait a Paris, le <?php echo date('d/m/y'); ?></p>
		<p>Signature du particulier, suivie de la mension manuscrite "bon pour accord".</p>
		<p>&nbsp;</p>
	</page_footer>

	<table style="vertical-align: top;">
		<tr>
			<td class="75p">
				<strong><?php echo $teamease['name']; ?></strong><br />
				<?php echo nl2br($teamease['address']); ?><br />
				<strong>SIRET:</strong> <?php echo $teamease['siret']; ?><br />
				<?php echo $teamease['email']; ?>
			</td>
			<td class="25p">
				<strong><?php echo $teamease['name']; ?></strong><br />
				<?php echo nl2br($teamease['address']); ?><br />
			</td>
		</tr>
	</table>

	<table style="margin-top: 80px;">
		<tr>
			<?php if($isinvoice != true) { ?>
			<td class="50p"><h2>Devis n°<?= $idReservation ?></h2></td>
			<?php } else { ?>
			<td class="50p"><h2>Facture n°<?= $idReservation ?></h2></td>
			<?php } ?>
			<td class="50p" style="text-align: right;">Emis le <?php echo date('d/m/y'); ?></td>
		</tr>
	</table>

	<table style="margin-top: 50px;" class="border">
		<thead>
			<tr>
				<th class="50p">Activité</th>
				<th class="15p">Participants</th>
				<th class="15p">Prix par participants (HT)</th>
				<th class="15p">Prix Unitaire</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($tasks as $task): ?>
			<tr>
				<td><?php echo $task['name']; ?></td>
				<td><?php echo $task['attendee']; ?></td>
				<td><?php echo $task['priceAttendee'] * 0.8; ?> &euro;</td>
				<td><?php echo $price * 0.8; ?> &euro;</td>
			</tr>
				<?php
    $price_tva = $task['price'] * 1.2;
    $total += $task['price'];
    $total_tva += $price_tva;
    ?>
			<?php endforeach; ?>

			<tr>
				<td style="padding-top: 50px;"></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>

			<tr>
				<td colspan="2" class="no-border"></td>
				<td style="text-align: center;" rowspan="3"><strong>Total:</strong></td>
				<td>HT : <?php echo $price * 0.8; ?> &euro;</td>
			</tr>
			<tr>
				<td colspan="2" class="no-border"></td>
				<td>TVA : <?php echo $total_tva - $total; ?> &euro;</td>
			</tr>
			<tr>
				<td colspan="2" class="no-border"></td>
				<td>TTC : <?php echo $price; ?> &euro;</td>
			</tr>
		</tbody>
	</table>

</page>

<?php
$content = ob_get_clean();
try {
  $pdf = new Html2Pdf('p', 'A4', 'fr');
  $pdf->writeHTML($content);
  if($isinvoice != true) {
	$pdf->output('Devis.pdf');
  } else {
	$invoice = $pdf->output('Facture.pdf', 'S');
  }
} catch (Html2PdfException $e) {
  $pdf->clean();
  $formatter = new ExceptionFormatter($e);
  echo $formatter->getHtmlMessage();
}


?>
