<html>
	<head>
		<meta charset = "utf-8">
		<title>ミッション５  簡易掲示板</title>
	</head>
	
	
	<body>	
	
		<?php
			$dsn='データベース名'; //データベースと接続
			$user='ユーザー名';
			$password='パスワード';
			$pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
			
			 // $sql ='DROP TABLES IF EXISTS tbtest';(テーブルを削除)
			 // $stmt = $pdo -> query($sql);
			  
			$sql = "CREATE TABLE IF NOT EXISTS tbtest"//テーブルを作成。
	       ."("
	       ."id INT AUTO_INCREMENT PRIMARY KEY,"
	       ."name char(32),"
	       ."comment TEXT,"
	       ."date DATETIME ,"
       	   ."password TEXT"
           .");";
           $stmt = $pdo -> query($sql);
           
            // $sql='SHOW TABLES';//(テーブルの作成を確認)
			//  $result=$pdo -> query($sql);
			//  foreach ($result as $row){
			// 		echo $row[0];
			// 	echo '<br>';
	        //    }
	        // echo "<hr>";	
	        
	        if(!empty($_POST["name"]) && (!empty($_POST["comment"])) && (!empty($_POST["pass"])) && (empty($_POST["editnumber"]))) {
	        	
	        		$sql = $pdo ->prepare("INSERT INTO tbtest (name,comment,date,password) VALUES(:name, :comment, :date, :password)");
	        		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        		$sql -> bindParam(':date', $date, PDO::PARAM_STR);
	        		$sql -> bindParam(':password', $pass, PDO::PARAM_STR);
	        		$name = $_POST["name"];
	        		$comment = $_POST["comment"];
	        		$date = date("Y-m-d H:i:s");
	        		$pass= $_POST["pass"];
	        		$sql -> execute();		
	       }
	       
	       	if(!empty($_POST["delete"])) {//削除対象番号を受け取る
	       			$deletenum =$_POST["delete"];
	       			$delpass = $_POST["delpass"];
	       			$sql = 'SELECT * FROM tbtest';
					$stmt = $pdo->query($sql);
					$results = $stmt->fetchAll();
					foreach ($results as $row){
							if(($row['id']==$deletenum)&&($row['password']==$delpass)){								
								$sql = 'delete from tbtest where id=:id';
								$id = $_POST["delete"];
								$stmt=$pdo->prepare($sql);
								$stmt->bindParam(':id',$id,PDO::PARAM_INT);
								
								$stmt->execute();
			            	}elseif(($row['id']==$deletenum)&&($row['password']!=$delpass)){
			            		echo "パスワードが間違っています";
			                }
			      }
	        }
	        
	        
	         if	(!empty($_POST["edit"])){//編集内容表示 editが空っぽじゃなかったら  
		  		$editnum = $_POST["edit"];
		  		$editpass= $_POST["editpass"]; 
		  		$sql = 'SELECT * FROM tbtest';
					$stmt = $pdo->query($sql);
					$results = $stmt->fetchAll();
					foreach ($results as $row){
							if(($row['id']==$editnum)&&($row['password']==$editpass)){	
									$editnum = $row['id'];
									$editname = $row['name'];
									$editcomm = $row['comment'];
									
							}elseif(($row['id']==$editnum)&&($row['password']!=$editpass)){
								echo "パスワードが間違っています";
							}
				}
		}
		
		if(!empty($_POST["name"]) && (!empty($_POST["comment"])) && (!empty($_POST["editnumber"]))) {//名前とコメントと編集番号が空でないとき	
				$editnumber = $_POST["editnumber"];  
				$sql = 'SELECT * FROM tbtest';
					$stmt = $pdo->query($sql);
					$results = $stmt->fetchAll();
					foreach ($results as $row){
						if($row['id']==$editnumber){							
	        			
	        			        				
	        				$sql = 'update tbtest set name=:name,comment=:comment,date=:date,password=:password where id=:id';
							$stmt = $pdo->prepare($sql);
							$stmt->bindParam(':name', $name, PDO::PARAM_STR);
							$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
							$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
	        				$stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
							$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
							$id = $_POST["editnumber"];
							$name = $_POST["name"];
	        				$comment = $_POST["comment"];
	        				$date= date("Y-m-d H:i:s"); 
	        				$pass= $_POST["pass"];
							$stmt -> execute();

	        			}
	        		}
	       }
	       
	     
	?>
	
	 <form method = "post" action = "mission_5-1.php"><!--入力フォーム-->	
		<input type = "text" name = "name" placeholder = "名前" value = "<?php if(!empty($editname)) {echo  $editname;}?>" size = 30><br>

		<input type = "text" name = "comment" placeholder = "コメント"  value ="<?php if(!empty($editcomm)) {echo $editcomm;}?>" size=30><br>
		
		<input type = "text" name = "pass" placeholder = "パスワード" value ="" size = 30>
		<input type = "submit"><br>
		<input type = "hidden" name = "editnumber" value = "<?php if(!empty($editnum)) {echo $editnum;}?>" size = 30>
		</form>
		
	
		<br>
		
		 <form method = "post" action = "mission_5-1.php"><!--削除番号指定用フォーム-->
		 <input type = "text" name = "delete" placeholder = "削除対象番号" size=30><br>
		 <input type = "text" name = "delpass" placeholder = "パスワード"  value ="" size = 30>
		 <input type = "submit" value = "削除">
		 </form>			 
		 
		  <br>
		   <form method = "post" action = "mission_5-1.php"><!--編集番号指定用フォーム-->
		   <input type = "text" name = "edit" placeholder = "編集対象番号" size=30><br>
		   <input type = "text" name = "editpass" placeholder = "パスワード" value ="" size = 30>
		   <input type = "submit" value = "編集">
		   </form>

	
	
	<?php
		$sql = 'SELECT * FROM tbtest';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach($results as $row){
					echo $row['id'].'   ';
					echo $row['name'].'   ';
					echo $row['comment'].'   ';			
					echo $row['date'].'<br>';
				echo "<hr>";
			}
	
	?>
	
	
	</body>
</html>