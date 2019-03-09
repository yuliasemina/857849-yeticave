<?php 
$lot_price = $lots['max_price'] ?: $lots['price'];
$min_bet = $lot_price + $lots['bet_step'];

$user_id = null;
if (isset($_SESSION['user'])) {
  $user = $_SESSION['user']; 
  $user_id = $user['id'];

} 


?>

    <nav class="nav">
     <ul class="nav__list container">
         <?php foreach ($categories as $category): ?>
           <li class="nav__item">
            <a href="all_lots.php?id=<?= intval($category['id']) ?>"><?= htmlspecialchars($category['category_name']) ?></a>
          </li>
        <?php endforeach ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $_GET['search'] ?></span>»</h2>
        <ul class="lots__list">
         <?php if (empty($lots)): ?>
         <h3 class="lot__title"> Ничего не найдено по вашему запросу</h3>
 <?php else: ?>
          <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= htmlspecialchars($lot['image']) ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><? htmlspecialchars($lot['category']) ?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= intval($lot['id']) ?>">
                        <?= htmlspecialchars($lot['title']) ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= price_format(htmlspecialchars($lot['price'])) ?></span>
                            </div>
                            <div class="lot__timer timer">
                                <?= time_interval($lot['date_end']); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach ?>
            <?php endif; ?>
        </ul>
      </section>

<?php if ($pages_count > 1): ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
          <?php if (($cur_page-'1') > '0'): ?>
          <a href="search.php?search=<?= $search ?>&page=<?=$cur_page-'1' ?>">Назад</a>
          <?php else: ?>
            <a>Назад</a>
          <?php endif; ?>
        </li>
      <?php foreach ($pages as $page): ?>
          <li class="pagination-item  
          <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>
          "><a href="search.php?search=<?= $search ?>&page=<?=$page ?>"><?=$page;?></a>
        </li>
      <?php endforeach; ?>
      <li class="pagination-item pagination-item-next">
        <?php if (($cur_page+'1') <= $pages_count): ?>
          <a href="search.php?search=<?= $search ?>&page=<?=$cur_page+'1' ?>">Вперед</a>
          <?php else: ?>
            <a>Вперед</a>
          <?php endif; ?>
        </li>
      </ul>
    <?php endif; ?>
  
    </div>
