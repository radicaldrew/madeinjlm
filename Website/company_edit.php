<!DOCTYPE HTML>
<html>

<link rel="stylesheet" type="text/css" href="css/demo.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<head >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Made In Jerusalem - Company List</title>

</head>
<?php
$debug = true;
include "connect.php"; //include php file of connection string.

$link=mysqli_connect($mysql_host, $mysql_user,$mysql_pass, $mysql_default_database);


function DisplaySQLTableData($link,$query)
{
	if ($result = $link->query($query)) {
		echo '<table border="1" bgcolor="white"><tr>';

		/* fetch associative array */
		while ($row = $result->fetch_row()) {

			for($i=0;$i<mysqli_num_fields($result);$i++)
				echo "<tr><td>" .CheckForImagesOrLinks($row[$i], $row[5])."</td></tr>" ;

		}
		echo "</table>";

		/* free result set */
		$result->free();
	}
	else echo 'ERROR:'.mysqli_error($link);
}

function CheckForImagesOrLinks($fieldvalue,$url)
{
	$result = $fieldvalue; //set result as same value by default;

	if($fieldvalue)
	{
		//set result data to image or link if applicable
		if(is_string($fieldvalue))
		{
			$file_extension = substr($fieldvalue,strlen($fieldvalue)-4,4);
			$imgPrefix = substr($fieldvalue,1,3);
			//echo $imgPrefix;

			if($file_extension==".png" || $file_extension == ".jpg" || $file_extension==".gif" || $file_extension=="jpeg")
			{
				$isImage = true;
				$result = "<img src='$fieldvalue' alt='$fieldvalue' style='max-height: 100px; max-width: 100px'>";
			}
			if(substr($result,0,4)=='<img' && $url)
				$result = str_replace('<img', "<a href='$url' alt='$url' ><img", $result).'</a>';
			if(!$isImage && substr($fieldvalue, 0,4)=="http")
					$result = "<a href='$fieldvalue'>$fieldvalue</a>";


		}

	}
	return $result;
}

function AddCompany()
{

	$name = $_POST["name"];
	$description = $_POST["description"];
	$sql = "INSERT INTO  company(name ,	description)
				 		  VALUES( $name, $description)";
	$link->query($sql);
}


//Try loading database objects




//echo 'var data = { "companies" : '.json_encode($array).' };';



?>

<body>
<form method="post" action="company_edit.php">
<header class="body"></header>
<div align="right">

<table>
	<tr>
		 <td></td>
	</tr>
</table>
</div>

  <div class="mainpage">
            <header>
            <br><br><br><br>
                <h1>Made In JLM - <span>Company List</span></h1>

            </header>
  </div>
<section class="section" >
<a href="company.php">Back To List</a>
<div align="center">

<?
$companyID = $_REQUEST["company_id"];

if($companyID)
{//dump selected company data on READ ONLY MODE
$query = "SELECT
		 tblCompany.picture
		,tblCompany.name AS CompanyName
		,tblCompany.description
		,listType.name 	 AS CompanyType
		,listIndustry.name  AS Industry
		,tblCompany.url
		from company as tblCompany
		JOIN list_industry as listIndustry ON tblCompany.industry_id = listIndustry.industry_id
		JOIN list_company_type as listType ON tblCompany.type_id = listType.company_type_id
		WHERE tblCompany.company_id =$companyID;";
DisplaySQLTableData($link,$query);
}
else //run the following code in case of ADD/EDIT a record (Editable mode)
{ ?>
			<table align=left>
				<tr>
					<td>

						 name <br>
						 <input type="text" id="name">
						 <br>
						 Choose Picture<br>
						<input type="file" name="picturefile" id="picturefile"><Br>
						description<Br>
						<input type="text" id="description">
					</td>

				</tr>
				<tr><td>
				<br>
					<input type="submit" value="Add Company" >
				</td></tr>
			</table>
<?php
}
/* close connection */
$link->close();
?>
							</div>
							</section>
<footer class="body">
</footer>

</form>
 </body>
</html>