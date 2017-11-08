<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>同人誌一覧</title>
<link rel=stylesheet href="main.css" type="text/css" charset="utf-8" />
<script src="jq/jquery-3.2.1.js" type="text/javascript"></script>
<script src="jq/jquery.tablesorter.min.js" type="text/javascript"></script>
<script type="text/javascript">
   $(document).ready(function()
       {
           $("#booksTable").tablesorter();
       }
   );
</script>
</head>
<body>
<h1>同人誌一覧</h1>

<ul class="func">
<li class="func_search">
<form action="search.php" method="get">
<input type="text" name="keyword" size="75" value="">
<input type="submit" value="検索">
</form>
</li>
</ul>
<?php
try{
  require_once 'DbManager.php';
  $db = getDb();
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}catch(PDOException $Exception){
  die('接続エラー：' .$Exception->getMessage());
}
try{
  $sql = "SELECT * FROM 同人 ORDER BY ジャンル ASC";
  $stmh = $db->prepare($sql);
  $stmh->execute();
}catch(PDOException $Exception){
  die('接続エラー：' .$Exception->getMessage());
}
?>
<table id="booksTable" class="books">
<thead>
  <tr>
    <th>タイトル</th>
    <th>ジャンル</th>
    <th>著者</th>
    <th>出版</th>
  </tr>
</thead>
<tbody>
<?php
while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
  ?>
    <tr>
    <td><?=htmlspecialchars($row['タイトル'])?></th>
		<td><a href="refine.php?column=ジャンル&text=<?=htmlspecialchars($row['ジャンル'])?>"><?=htmlspecialchars($row['ジャンル'])?></a></th>
		<td><a href="refine.php?column=著者&text=<?=htmlspecialchars($row['著者'])?>"><?=htmlspecialchars($row['著者'])?></a></th>
		<td><a href="refine.php?column=出版&text=<?=htmlspecialchars($row['出版'])?>"><?=htmlspecialchars($row['出版'])?></a></th>
    </tr>
<?php 
}
$db = null;?>
</tbody></table>
</body>
</html>
