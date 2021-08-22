<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<head>
		<style type="text/css">
			table>thead>tr>th, table>tbody>tr>td{
				text-align: center;
				height: 20px;
			}
		</style>
	</head>

	<body>
		<table>
			<thead>
			<tr>
				<th>std_id</th>
				<th>std_name</th>
				<th>subject_id</th>
				<th>session_id</th>
				<th>{{date("d-m-Y")}}</th>
			</tr>
			</thead>
			<tbody>
			@for($x=0; $x<count($studentList); $x++)
				<tr>
					<td>{{$studentList[$x]['id']}}</td>
					<td>{{$studentList[$x]['name']}}</td>
					<td>{{$attendanceInfo->subject}}</td>
					<td>{{$attendanceInfo->session}}</td>
					<td>P / A</td>
				</tr>
			@endfor
			</tbody>
		</table>
	</body>
</html>
