<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>絞り込み</title>
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
  $column = $_GET['column'];
  $char = $_GET['text'];
  if($column == "タイトル" || $column == "ジャンル" || $column == "著者" || $column == "出版"){
    $sql = "SELECT * FROM 同人 WHERE $column LIKE :char";
    $stmh = $db->prepare($sql);
    $stmh->bindValue(':char', $char, PDO::PARAM_STR);
    $stmh->execute();
  }
  else throw NEW Exception ("指定された方法以外で絞り込みが実行された可能性があります。");
}catch(Exception $Exception){
  echo '検索エラー：' .$Exception->getMessage();
  die();
}
?>
<h1>絞り込み <?php echo $_GET['column'].": ".$_GET['text'] ?></h1>
<table class="books">
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
    <td><a href="edit.php?id=<?=htmlspecialchars($row['蔵書番号'])?>"><?=htmlspecialchars($row['タイトル'])?></a></th>
		<td><a href="refine.php?column=ジャンル&text=<?=htmlspecialchars($row['ジャンル'])?>"><?=htmlspecialchars($row['ジャンル'])?></a></th>
		<td><a href="refine.php?column=著者&text=<?=htmlspecialchars($row['著者'])?>"><?=htmlspecialchars($row['著者'])?></a></th>
		<td><a href="refine.php?column=出版&text=<?=htmlspecialchars($row['出版'])?>"><?=htmlspecialchars($row['出版'])?></a></th>
    </tr>
    <?php
}
$db = null;
?>
</tbody></table>
<a href="./">一覧に戻る</a>
</body>
</html>
