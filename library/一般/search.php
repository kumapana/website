<?php
  function h($str){
    echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
  function hss($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>検索結果</title>
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
  #die('接続エラー：' .$Exception->getMessage());
  die('接続エラー');
}
try{
  if(empty($_GET['keyword'])){
    #throw new Exception("検索キーワードが入力されていません");
    echo "検索エラー: 検索キーワードが入力されていません。";
  }
  $char = $_GET['keyword'];
  $sql = "SELECT * FROM 一般 WHERE タイトル LIKE ? or 著者 LIKE ? or イラスト LIKE ? or 出版 LIKE ?";
  $stmh = $db->prepare($sql);
  for ($i=1;$i<=4;$i++){
    $stmh->bindValue($i, '%'.$char.'%', PDO::PARAM_STR);
  }
  $stmh->execute();
}catch(Exception $Exception){
  #echo '検索エラー; ：' .$Exception->getMessage();
  echo "検索エラー";
  die();
}
?>
<h1><?php echo "検索結果: ".hss($_GET[keyword])?></h1>
<table class="books">
<thead>
  <tr>
    <th>タイトル</th>
    <th>著者</th>
    <th>イラスト</th>
    <th>出版</th>
    <th>種別</th>
  </tr>
</thead>
<tbody>
<?php
while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
  ?>
    <tr>
    <td><?php h($row['タイトル'])?></th>
		<td><a href="refine.php?column=著者&text=<?php h($row['著者'])?>"><?php h($row['著者'])?></a></th>
		<td><a href="refine.php?column=イラスト&text=<?php h($row['イラスト'])?>"><?php h($row['イラスト'])?></a></th>
		<td><a href="refine.php?column=出版&text=<?php h($row['出版'])?>"><?php h($row['出版'])?></a></th>
		<td><a href="refine.php?column=種別&text=<?php h($row['種別'])?>"><?php h($row['種別'])?></a></th>
    </tr>
    <?php
}
$db = null;
?>
</tbody></table>
<a href="./">一覧に戻る</a>
</body>
</html>
