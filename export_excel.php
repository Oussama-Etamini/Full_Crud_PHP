<?php
	header("Content-Type: application/xls");    
	header("Content-Disposition: attachment; filename=users_list.xls");  
	header("Pragma: no-cache"); 
	header("Expires: 0");
	require_once 'db_conn.php';
	$output = "";
	$output .="
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Email</th>
					<th>Gender</th>
				</tr>
			<tbody>
	";
	$query = $conn->query("SELECT * FROM `users`");
	while($fetch = $query->fetch_array()){
	$output .= "
				<tr>
					<td>".$fetch['id']."</td>
					<td>".$fetch['first_name']."</td>
					<td>".$fetch['last_name']."</td>
					<td>".$fetch['email']."</td>
					<td>".$fetch['gender']."</td>
				</tr>
	";
	}
	$output .="
			</tbody>
 
		</table>
	";
	echo $output;
?>