<?php
/**
 * @사용법
 
		
		- 전체 표시
		
			search-directory.php 로만 접속하면 전체 정보를 표시한다.
			
		- 검색 표시.
		
			( 중요: 검색어는 가능하면 전체 업무 문장을 다 기록하는 것이 좋다. )
		
			search-directory.php?q=ecc 와 같이
		
			q 변수에 검색어를 지정하면 해당 검색어가 들어간 업무를 진행하는 이민국 분소를 출력한다.
			
			이 때 대소문자는 구분하지 않고 모두 검색한다.

	@사용 예제
	
		- 학생 비자 변경(신청)
		http://wiki.philgo.com/skins/Vector/search-directory.php?q=Conversion to Student Visa
		
		
		- 학생 비자 연장
		http://wiki.philgo.com//Vector/search-directory.php?q=Extension of Student Visa
		
		- 제목과 꼬리말을 없애는 방법
		
			/search-directory.php?q=Extension%20of%20Student%20Visa&title=no&copyright=no
			
		- 영어 표시 옵션
			lang=en
		- 설명 표시 옵션
			desc=no
			
			
		
	@중요
		이민국 문서가 변경되면 같이 변경을 해야 한다.
 
 */
$directory = getDirectory();
$lines = explode("\n", $directory);
$key = null;
foreach ( $lines as $line ) {
	$line = trim($line);
	if ( empty($line) ) continue;
	if ( $line[0] == '*' ) {
		$line = substr($line, 2);
		$arr[$key][] = $line;
	}
	else {
		$key = $line;
		$arr[$key] = array();
	}
}

show_header();
if ( !empty ($_GET['q']) ) {
	if ( empty($_GET['q']) || $_GET['q'] == 'how to use' ) {
		show_how_to_use();
	}
	else show_search_result($arr, $_GET['q']);
}
else {
	show_how_to_use();
	show_all($arr);
}
show_footer();

function show_search_result(array &$arr, $q) {
	
	$res = array();
	foreach ( $arr as $key => $sub ) {
		foreach ( $sub as $task ) {
			if ( stripos($task, $q) !== false ) {
				$res[] = $key;
				break;
			}
		}
	}
	if ( empty($res) ) echo "ATTENTION: No Immigration Field Office/District Office/One stop office handles '$q', but Bureau of Immigration Head Office may handle it.<br>알림: '$q' 와 관련된 업무를 처리하는 분소가 없습니다. 어쩌면, 마닐라의 이민국 본청에서 처리를 할 수 있습니다.";
	else {
		if ( ! isset($_GET['title']) ) echo "<h2>$q 업무를 담당 하는 곳</h2>";
		echo "<ul>";
		//echo "<li><b>Bureau of Immigration Head Office</b> <b style='color:blue;'>may</b> handle the task</li>";
		foreach ( $res as $office ) {
			echo "<li>$office</li>";
		}
		echo "</ul>";
		if ( ! isset($_GET['desc']) )  {
			if ( isset($_GET['lang']) && $_GET['lang'] == 'en' ) echo "<div class='note'>Most of task done in Field Office/District Offiice/One stop office can be cone in Bureau of Immigration Head Office.</div>";
			else echo "<div class='note'>참고: 이민국분소에서 하는 대부분의 업무는 마닐라 이민국 본청에서 처리 할 수 있습니다.</div>";

			echo "<div class='note'>Embed Code:<br>
&lt;iframe name='document' width='100%' height='4096'  src='http://phildic.org/skins/Vector/search-directory.php?q=$_GET[q]&title=no&copyright=no&lang=en&desc=no'>&lt;/iframe>
</div>";

		}
	}
}



function show_all(array &$arr) {
	$count = 0;
	foreach ( $arr as $key => $sub ) {
		echo "<h1>$key</h1>";
		echo "<ul>";
		foreach ( $sub as $task ) {
			$count ++;
			echo "<li>$task</li>";
		}
		echo "</ul>";
	}
	echo "Number of Immigration branches: $count";
}


function show_how_to_use()
{
echo<<<EOH
	<h2 class='note' style='margin-top:0; color:white;'>How To Use: Immigration Task Search System</h2>
	
	<ul>
	
		<li> To see all the list of Immigration Branch(District Office/Field Office/One Stop Office)
				access below;<br>
				http://wiki.philgo.com/skins/Vector/search-directory.php
		</li>
		<li>
			To search immigration branch of a task, access like below;<br>
				http://wiki.philgo.com/skins/Vector/search-directory.php?q=search keyword<br>
				Where 'search keyword' is the task (or part of the task) you want to search.
		</li>
		<li>
			The 'search keyword' should in full. If you input part of the task like 'Student Visa', then it will list all branch which that handle Student Visa and that is not what you may want.<br>
			If you want to extend student visa, put 'Extension of Student Visa' instead of 'Student Visa'
			because Application and Extension of Student Visas are handled in different branch. One branch may only do application and the other may only do extension.
		</li>
		<li>
			Option:
			<ul>
				<li>title=no<br>
					removes title
				</li>
				<li>copyright=no<br>
					removes copyright
				</li>
				<li>lang=en<br>
					display in English only.
				</li>
			</ul>
		</li>
	</ul>
	
EOH;
}



function show_header()
{
	echo<<<EOH
<!doctype html>
<html>
	<head>
		<meta charset='UTF-8'>
		<style>
			body {
				margin:0;
				padding:0;
				font-size:10pt;
				font-family:'Malgun Gothic',Arial;
				line-height:180%;
			}
			h1, h2, h3 {
				margin:0;
				padding:0;
			}
			a {
				color:black;
				text-decoration:none;
			}
			.note {
				margin:0.4em 0;
				padding: 1em 2em;
				background-color:#a5c9de;
			}
		</style>
	</head>
	<body>
EOH;
}
function show_footer()
{

$copyright = "

		<footer class='note'>
			
			<a href='http://wiki.philgo.com'>
			
				본 정보는 필고 필리핀 정보 백과에 의해서 제공됩니다.<br>
				This information is provided by 
			
			http://wiki.philgo.com</a>
			
			<br>
			
			<a href='?q=how to use'>How To Use : Click here to know how to use.</a>
			
		</footer>
";
	if ( ! isset($_GET['title']) ) echo $copyright;
	echo "
	</body>
</html>";
}


function getDirectory() {

$directories = <<<EOH
Angeles Immigration Field Office

  * Amendment/Correction of Admission

  * Annual Report

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Re-stamping: Transfer of Admission from Old/Lost to New Passport

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Aparri Immigration Field Office

  * Extension of Authorized Stay of Temporary Visitors

Bacolod Immigration Field Office

  * Annual Report

  * Conversion to Student Visa

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Student Visa

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Baguio Immigration District Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Annual Report

  * Application for Certificate of Exemption

  * Application for Re-entry Permit

  * Application for Special Return Certificate

  * Amendment to Permanent Non-Quota Immigrant Visa by Marriage

  * Conversion to Non-Quota Immigrant by Marriage

  * Conversion to Non-Quota Visa of a Former Filipino Citizen Naturalized in a Foreign Country

  * Conversion to Pre-arranged Employee Visa-Commercial

  * Conversion to Pre-arranged Employee Visa-Missionary

  * Conversion to Student Visa

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Pre-Arranged Employee Visa - Commercial

  * Extension of Pre-Arranged Employee Visa - Missionary

  * Extension of Student Visa

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Balabac Immigration Field Office

  * Immigration Border Formalities

  * Random Checking

  * Vessel Boarding

  * Visa Extension

Balanga Immigration Field Office

  * Annual Report

  * Boarding Formalities

  * Clearance of Outgoing Vessel

  * Downgrading of Visa

  * Emigration Clearance Certificate (ECC)



  * Extension of Authorized Stay of Temporary Visitors

  * FAB Investor's Visa

  * FAB Dependent's Visa

  * Issuance of Arrival and Departure Card

  * Payment of Crewlist Fee

  * Special Work Permit (SWP)

  * Restamping: Transfer of Admission from Old/Lost to New Passport

Batangas Immigration Field Office

  * Amendment from Probationary Non-Quota Immigrant Visa by Marriage to Permanent Visa

  * Annual Report

  * Conversion to Non-Quota Immigrant by Marriage

  * Conversion to Non-quota Immigrant Visa of a Former Filipino Citizen Naturalized in a Foreign Country

  * Conversion to Pre-Arranged Employee Visa - Commercial

  * Conversion to Student Visa

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Pre-Arranged Employee Visa - Commercial

  * Extension of Student Visa

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Batuganding Immigration Border Crossing Station

  * Annual Report

  * Intelligence / Surveillance and Monitoring

  * Vessel Boarding

Bislig Immigration Field Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Annual Report

  * Boarding Formalities

  * Conversion to Student Visa

  * Derogatory Certification

  * Extension of Authorized Stay of Temporary Visitors

  * Immigration Port Clearance

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Boac Immigration Field Office

  * Annual Report

  * Extension of Authorized Stay of Temporary Visitors

BOI Makati Immigration Extension Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * ACR I-Card Capturing and Fingerprinting

  * Annual Report

  * Amendment from Probationary to Permanent

  * Application for Certificate of Exemption

  * Cancellation of Visa (SIRV/SRRV)

  * Cancellation of ACR I-Card for Downgrading/Expired I-Card

  * Change of Address (For I-Card Renewal)

  * Conversion to Pre-Arranged Employee Visa-Commercial

  * Conversion of Treaty Trader's Visa



  * Conversion to  Special Non-Immigrant Visa under Sec 47(a) 2

  * Conversion to Non-Quota Immigrant Visa of a Former Filipino Citizen Naturalized in a Foreign Country

  * Conversion to Non-Quota Immigrant Visa of a Previous Permanent Resident  Returning from a Temporary Visit Abroad

  * Conversion to Special Non-immigrant  Visa under EO 226, as amended by RA 8756

  * Conversion to Non-Quota Immigrant Visa by Marriage

  * Downgrading of Visa

  * Emigration Clearance Certificate (ECC)

  * ECC / CE Renewal

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Special Non-immigrant  Visa under EO 226, as amended by RA 8756

  * Extension of Special Non-Immigrant Visa under Sec 47(a) 2

  * Extension of Pre-Arranged Employee Visa- Commercial

  * Extension of Treaty Trader's Visa

  * Interim Extension (Grace Period)

  * Issuance of Certificate of Pending Application

  * Lifting of Hold Departure Order/ Blacklist (For BOI Request)

  * Issuance and Re-issuance of Special Return Certificate/Re-entry Permit

  * Re-registration

  * Revalidation of Visa

  * Re-stamping: Transfer of Admission from Old/Lost to New Passport

  * Special Investor's Residence Visa (SIRV)

  * Special Resident Retiree's Visa (SRRV)

  * Special Work Permit (SWP-Non-Artist)

  * Visa Upon Arrival (endorsed by Board of investment)

Bongao Immigration Field Office

  * Clearing of passenger vessel

  * Extension of Authorized Stay of Temporary Visitors

  * Vessel Boarding

Boracay Immigration Field Office

  * ACR I-Card Issuance (For Tourist)

  * Annual Report

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Motion for Reconsideration in Visa Extensions

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Brooke's Point Border Crossing Station

  * Boarding Formalities

Butuan Immigration District Office

  * Annual Report

  * Boarding Formalities

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Student Visa

  * Special Study Permit (SSP)



Calapan Immigration District Office

  * Annual Report

  * Clearance of Outgoing Vessel

  * Derogatory Certification

  * Extension of Authorized Stay of Temporary Visitors

  * Issuance of Arrival and Departure Card
  
Calbayog Immigration Field Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Extension of Authorized Stay of Temporary Visitors

Cagayan de Oro Immigration District Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Amendment from Probationary Non-Quota Immigrant Visa by Marriage to Permanent Visa

  * Annual Report

  * Boarding Formalities

  * Conversion to Non-Quota Immigrant by Marriage

  * Conversion to Non-quota Immigrant Visa of a Former Filipino Citizen Naturalized in a Foreign Country

  * Conversion to Pre-Arranged Employee Visa - Commercial

  * Conversion to Pre-Arranged Employee Visa - Missionary

  * Conversion to Student Visa

  * Derogatory Certification

  * Emigration Clearance Certificate (ECC)

  * Extension Authorized Stay of Temporary Visitors

  * Extension of Pre-Arranged Employee Visa - Commercial

  * Extension of Pre-Arranged Employee Visa - Missionary

  * Extension of Student Visa

  * Immigration Port Clearance

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Cauayan Immigration Field Office

  * Annual Report Payment

  * Application for Change of Address

  * Application for Certificate of Exemption

  * Application of Temporary Visitor's Visa I-Card

  * Application for School Accreditation

  * Conversion of Student Visa

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Student Visa

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

  * Visa Waiver

Cebu Immigration District Office

  * ACR I-Card Issuance, Re-issuance and Renewal

  * Amendment to Permanent Non-Quota Immigrant Visa by Marriage

  * Amendment/Correction of Admission

  * Annual Report

  * Certificate of Exemption (Leaving for Good)

  * Certified True Copy of Immigration Documents

  * Change of Address



  * Conversion to Non-Quota Immigrant Visa by Marriage (Probationary)

  * Conversion to Non-Quota Immigrant Visa of a Previous Permanent Resident Returning From a Temporary Visit Abroad

  * Conversion to Non-quota Immigrant Visa of a Former Filipino Citizen Naturalized in a Foreign Country

  * Conversion to Permanent Resident Visa (Probationary) of a Chinese National Married to a Filipino Citizen

  * Conversion to Pre-Arranged Employee Visa - Commercial

  * Conversion to Pre-Arranged Employee Visa - Missionary

  * Conversion to Temporary Resident's Visa (TRV)

  * Conversion to Treaty Trader's Visa

  * Conversion to Student Visa

  * Downgrading

  * Dual Citizenship (RA 9225)

  * Emigration Clearance Certificate (ECC-A)

  * Emigration Clearance Certificate with Re-entry Permit (RP)/Special Return Certificate

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Pre-Arranged Employee Visa - Commercial

  * Extension of Pre-Arranged Employee Visa - Missionary

  * Extension of Temporary Resident's Visa (TRV)

  * Extension of Treaty Trader's Visa

  * Extension of Student Visa

  * Interim Extension (Grace Period)

  * Implementation of Immigrant Visa, Non-Quota Immigrant Visa by Marriage and Student Visa

  * Immigration Port Clearance

  * Long Stay Visitor's Visa Extension

  * Motion for Reconsideration

  * Provisional Work Permit (PWP)

  * Revalidation

  * School Accreditation

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Clark Immigration One Stop Shop

  * ACR I-Card Issuance, Reissuance and Renewal

  * Annual Report

  * Change of Address

  * Change of Status

  * Downgrading of Visa from SCWV to Temporary Visitor's Visa

  * Emigration Clearance Certificate (ECC)

  * Provisionary Work Permit (PWP)

  * Re-registration upon reaching 14 years old

  * Conversion to Subic Clark Working Visa (SCWV) and Dependents

  * Conversion to Subic Clark Investor's Visa (SCIW) and Dependents

  * Special Work Permit Cotabato Immigration District Office

  * Annual Report

  * Boarding Formalities

  * Emigration Clearance Certificate (ECC) / Exit Clearance

  * Extension of Authorized Stay of Temporary Visitors

  * Special Work Permit (SWP)



Dagupan Immigration Field Office

  * Annual Report

  * Boarding Formalities

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Petition for Reacquisition or Retention of Philippine Citizenship

  * School Accreditation

  * Special Study Permit (SSP)

  * Visa Waiver

  * Visa Waiver Extension

Davao Immigration District Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * ACR I-Card Certification

  * Amendment from Probationary Non-Quota Immigrant Visa by Marriage to Permanent Visa

  * Annual Report

  * Boarding Formalities

  * Conversion to Non-Quota Immigrant by Marriage

  * Conversion to Non-Quota Immigrant Visa of a Former Filipino Citizen Naturalized in a Foreign Country

  * Conversion to Pre-Arranged Employee Visa - Commercial

  * Conversion to Pre-Arranged Employee Visa - Missionary

  * Conversion to Temporary Resident Visa (TRV)

  * Conversion to Student Visa

  * Derogatory Certification

  * Downgrading of Visa

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Pre-Arranged Employee Visa - Commercial

  * Extension of Pre-Arranged Employee Visa - Missionary

  * Extension of Temporary Resident Visa (TRV)

  * Extension of Student Visa

  * Grace Period from Student Visa to Tourist Visa

  * Long Stay Visitor's Visa Extension (LSVVE)

  * Petition for Reacquisition or Retention of Philippine Citizenship

  * Provisional Work Permit (PWP)

  * Revalidation of Visa

  * School Accreditation

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Dumaguete Immigration District Office

  * Annual Report

  * Conversion to Student Visa

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Student Visa

  * Petition for Reacquisition or Retention of Philippine Citizenship

  * School Accreditation

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Gaisano Immigration Satellite Office

  * Annual Report

  * Extension of Authorized Stay of Temporary Visitors



General Santos Immigration Field Office

  * ACR I-Card Issuance for Tourist

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Payment of Visa Crewlist Fee

  * Special Work Permit (SWP)

Glan Immigration Field Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Annual Report

  * Boarding Formalities

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Payment of Visa Crewlist Fee

  * Special Work Permit (SWP)

Iligan Immigration Field Office

  * ACR I-Card, after 59 days of stay for Tourist Visa Extension

  * Annual Report

  * Boarding Formalities

  * Extension of Authorized Stay of Temporary Visitors

  * Motion for Reconsideration

  * Visa Crewlist Fee

  * Visa Waiver

Iloilo Immigration District Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Annual Report

  * Extension of Authorized Stay of Temporary Visitors

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Jolo Immigration Field Office

  *  Annual Report

  * Derogatory Certification

  * Extension of Authorized Stay of Temporary Visitors

  * Issuance of Arrival and Departure Clearances of Philippine Registered Local Vessels

  * Visa Waiver Extension

Kalibo Immigration Field Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Annual Report

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Motion for Reconsideration in Visa Extensions

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Laoag Field Immigration Office

  * Annual Report

  * Application for Certificate of Exemption

  * Boarding Formalities

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Immigration Port Clearance

  * Special Study Permit (SSP)



  * Special Work Permit (SWP)

  * Temporary Resident Visa (TRV)

Legaspi Immigration District Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Application for Temporary Visitor's  Visa (TVV) ACR I-Card

  * Application for Citizenship Re-acquisition (RA 9225)

  * Applications for Visa Conversion or Amendment (Sec. 9 & 13 Series [except Quota Immigrants])

  * Amendment of ACR I-Card Entries (Address, Status,etc.)

  * Annual Report

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Student Visa

  * Extension of Pre-arranged Employee Visa- Commercial

  * Extension of Pre-arranged Employee Visa- Missionary

  * Extension of Temporary Resident Visa (TRV)

  * Issuance of Special Return Certificate (SRC)

  * Issuance of Re-entry Permit (RP)

  * Receiving of Deportation Complaints for endorsement to Main Office

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

  * Vessel Boarding Formalities

Lucena Immigration Field Office

  * Annual Report

  * Application for Updating and Motion for Reconsideration of Temporary Visitors

  * Boarding Formalities

  * Certified True Copy of Annual Report Payment

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Student Visa

  * Immigration Port Clearance

  * School Accreditation

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Mariveles Immigration One Stop Shop

  * Annual Report

  * FAB Investor's Visa (FIV)

  * FAB Dependent's Visa

  * FAB Working Visa

Naga Immigration Field Office

  * Annual Report

  * Boarding Formalities

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Petition for Inclusion of Dependent(s) under Sec. 4 of R.A. 9225

  * Petition for Reacquisition or Retention of Philippine Citizenship

  * School Accreditation

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)



Olongapo Immigration Field Office

  * Annual Report (Acceptance of Payment only)

  * Boarding Formalities

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Re-stamping: Transfer of Admission from Old/Lost to New Passport

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

  * Acceptance of Complaints against Foreigners

  * Implements Office  Memorandum No. RBR-00-27 (Permanent Immigration Guidelines for U.S. personnel under the Visiting Forces Agreement (VFA)

Ozamis Immigration Field Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Annual Report

  * Boarding Formalities

  * Clearance of Outgoing Vessel

  * Derogatory Certification

  * Extension of Authorized Stay of Temporary Visitors

  * Immigration Port Clearance

  * Payment of Visa Crewlist Fee
  
PEZA Immigration Extension Office

  * Amendment to Permanent Non-Quota Immigrant Visa by Marriage

  * Conversion to Special Non-Immigrant Visa under Sec. 47(a)(2)

  * Conversion to Special Non-Immigrant Visa Under EO 226, as Amended by R.A. No. 8756

  * Conversion to Non-Quota Immigrant Visa by Marriage

  * Conversion to Non-Quota Immigrant Visa of a Child Born of an Immigrant Mother

  * Conversion to Non-Quota Immigrant Visa of a Child Born Subsequent to the Issuance of Immigrant Visa of the Accompanying Parent

  * Conversion to Non-Quota Immigrant Visa of a Previous Permanent Resident Returning from a Temporary Visit Abroad

  * Conversion to Non-Quota Immigrant Visa of a Former Filipino Citizen Naturalized in a Foreign Country

  * Conversion to Pre-Arranged Employee Visa (Commercial)

  * Conversion to Pre-Arranged Employee Visa (Commercial-Top 1000)

  * Conversion to Treaty Trader's Visa

  * Conversion to Treaty Trader's Visa (Top 1000)

  * Downgrading of Visa

  * Extension of Special Non-Immigrant Visa under Sec. 47(a)(2)

  * Extension of Pre-Arranged Employee Visa (Commercial)

  * Extension of Pre-Arranged Employee Visa (Commercial-Top 1000)

  * Extension of Special Non-Immigrant Visa Under EO 226, as Amended by R.A. No. 8756

  * Extension of Treaty Trader's Visa

  * Extension of Treaty Trader's visa (Top 1000)

  * Interim Extension (Grace Period)

  * Provisional Work Permit (PWP)

  * Revalidation



  * Re-Stamping of Visa

  * Special Work Permit (SWP)

Puerto Princesa Immigration Field Office

  * Assistance in Preparation of Requirements for ACR I-Card for Temporary Visitors only

  * Apprehension and Imposition of Administrative Fine on Illegal Entrants

  * Boarding of Vessels

  * Extension of Authorized Stay of Temporary Visitors

  * Illegal Entrants

  * Motion for Reconsideration of Extension of Stay

  * Payment of Crewlist Fee/Imposition of Administrative Fine

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

  * Vessel Boarding

San Fernando Immigration District Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Amendment from Probationary Non-Quota Immigrant Visa by Marriage to Permanent Visa

  * Annual Report

  * Conversion to Non-Quota Immigrant by Marriage

  * Conversion to Non-quota Immigrant Visa of a Former Filipino Citizen Naturalized in a Foreign Country

  * Conversion to Pre-Arranged Employee Visa - Commercial

  * Conversion to Pre-Arranged Employee Visa - Missionary

  * Downgrading of Visa

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Pre-Arranged Employee Visa - Commercial

  * Extension of Pre-Arranged Employee Visa - Missionary

  * Special Study Permit (SSP)

SM North Immigration Satellite Office

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Special Study Permit (SSP)

Sta. Rosa Immigration Field Office

  * Annual Report

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Motion for Reconsideration of Visa Extension

  * Petition for Reacquisition or Retention of Philippine Citizenship

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Subic Immigration One Stop Shop

  * ACR I-Card Issuance, Reissuance and Renewal

  * Amendment for Change of Address

  * Annual Report

  * Downgrading of Status from SSCWV to Temporary Visitor's Visa

  * Provisional Permit to Work (PPW)

  * Re-registration upon reaching 14 years of age

  * Re-stamping: Transfer of Admission from Old/Lost to New Passport

  * Special Subic Clark Dependent Visa (SSCDV)

  * Special Subic Clark Investor's Visa (SSCIV)

  * Special Subic Clark Working Visa (SSCWV)

  * Special Working Permit (SWP)

Surigao Immigration Field Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Annual Report

  * Derogatory Certification

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Immigration Clearance for Vessel

Tacloban Immigration District Office

  * ACR I-Card Renewal

  * Amendment from Probationary Non-Quota Immigrant Visa by Marriage to Permanent Visa

  * Annual Report

  * Conversion to Non-Quota Immigrant by Marriage

  * Conversion to Pre-Arranged Employee Visa - Commercial

  * Conversion to Pre-Arranged Employee Visa - Missionary

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Pre-Arranged Employee Visa - Commercial

  * Extension of Pre-Arranged Employee Visa - Missionary

  * Provisionary Work Permit (PWP)

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Taganak Immigration Field Office

  * Immigration Border Formalities

  * Vessel Boarding

Tagbilaran Immigration Field Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Annual Report

  * Boarding Formalities

  * Change of Address

  * Clearance of Outgoing Vessel

  * Extension of Authorized Stay of Temporary Visitors

  * Payment of Visa Crewlist Fee

  * Special Student Permit

  * Special Work Permit

Taytay Immigration Field Office

  * Annual Report

  * Conversion to Non-Quota Immigrant by Marriage

  * Conversion to Non-quota Immigrant Visa of a Former Filipino Citizen Naturalized in a Foreign Country

  * Conversion to Pre-Arranged Employee Visa - Commercial

  * Conversion to Pre-Arranged Employee Visa - Missionary

  * Conversion to Returning Resident

  * Conversion to Treaty Trader's Visa

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Pre-Arranged Employee Visa - Commercial

  * Extension of Pre-Arranged Employee Visa - Missionary

  * Petition for Reacquisition or Retention of Philippine Citizenship

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Tibanban Immigration Border Crossing Station

  * Annual Report

  * Border Crossing Activity

  * Border Crossing Cards and Departure Clearance

  * Cancellation of ACR I-Card

  * Immigration Border Formalities

  * Intelligence / Surveillance and Monitoring

  * Vessel Boarding

Tuguegarao Immigration District Office

  * Conversion to Student Visa

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Student Visa

  * Special Study Permit (SSP)

  * Special Work Permit (SWP)

Zamboanga Immigration Field Office

  * ACR I-Card Issuance, Reissuance and Renewal

  * Annual Report

  * Boarding Formalities

  * Conversion to Non-Quota Immigrant by Marriage

  * Conversion to Pre-Arranged Employee Visa - Commercial

  * Conversion to Pre-Arranged Employee Visa - Missionary

  * Conversion to Student Visa

  * Derogatory Certification

  * Emigration Clearance Certificate (ECC)

  * Extension of Authorized Stay of Temporary Visitors

  * Extension of Student Visa

  * Motion for Reconsideration in Visa Extensions

  * School Accreditation

  * Special Study Permit (SSP)
EOH;
	return $directories;
}
  
  
