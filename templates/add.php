      <nav class="nav">
        <ul class="nav__list container">
         <?php foreach ($categories as $category): ?>
           <li class="nav__item">
            <a href="all_lots.php?id=<?= intval($category['id']) ?>"><?= htmlspecialchars($category['category_name']) ?></a>
          </li>
        <?php endforeach ?>
      </ul>
    </nav> 
    <form class="form form--add-lot container <?= isset($errors) ? "form--invalid" : "" ?> " action="add.php" method="post" enctype="multipart/form-data">
      <h2>Добавление лота</h2>
      <div class="form__container-two">
 
        <div class="form__item <?= isset($errors['name']) ? "form__item--invalid" : "" ?>"> 
          <label for="lot-name">Наименование</label>
          <input id="lot-name" type="text" name="name" value ="<?= $_POST['name'] ?? ''?>" placeholder="Введите наименование лота" required>
          <span class="form__error">
            <?= $errors['name'] ?? "" ?>
          </span>
        </div>
 
        <div class="form__item <?= isset($errors['category_id']) ? "form__item--invalid" : "" ?>">
          <label for="category">Категория</label>
          <select id="category" name="category_id" required>
            <option value="" disabled selected>Выберите категорию</option>
            <?php foreach ($categories as $category): ?>
              <option 
                value="<?= $category['id'] ?>" 
                <?= $category['id'] === (int) $_POST['category_id'] ? 'selected' : ''  ?>
              >
                <?= htmlspecialchars($category['category_name']) ?>    
              </option>
              <?php endforeach ?>
            </select>
            <span class="form__error">
              <?= $errors['category_id'] ?? "" ?>
            </span>
          </div>
        </div>

        <div class="form__item form__item--wide <?= isset($errors['description']) ? "form__item--invalid" : "" ?>">
          <label for="message">Описание</label>
          <textarea id="message" name="description" placeholder="Напишите описание лота" 
          required><?= htmlspecialchars($_POST['description']) ?? '' ?></textarea>
          <span class="form__error">
            <?= $errors['description'] ?? "" ?>
          </span>
        </div>
 
        <div class="form__item form__item--file <?= isset($errors['image']) ? "form__item--invalid" : "" ?>">
          <label>Изображение</label>
          <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
              <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
          </div>
          <div class="form__input-file">
            <input class="visually-hidden" name="image" type="file" id="photo2" value="">
            <label for="photo2">
              <span>+ Добавить</span>
            </label>
          </div>
          <span class="form__error">
              <?= $errors['image'] ?? "" ?>
            </span>
        </div>
        <div class="form__container-three">
 
          <div class="form__item form__item--small <?= isset($errors['start_price']) ? "form__item--invalid" : "" ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="start_price" 
            value ="<?= $_POST['start_price'] ?? ''?>" placeholder="0" required>
            <span class="form__error">
              <?= $errors['start_price'] ?? "" ?>
            </span>
          </div>
 
          <div class="form__item form__item--small <?= isset($errors['bet_step']) ? "form__item--invalid" : "" ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="bet_step"
            value ="<?= $_POST['bet_step'] ?? ''?>" placeholder="0" required>
            <span class="form__error"><?= $errors['bet_step'] ?? "" ?></span>
          </div>

          <div class="form__item <?= isset($errors['date_end']) ? "form__item--invalid" : "" ?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date" 
            value ="<?= $_POST['date_end'] ?? ''?>" name="date_end" required>
            <span class="form__error">
              <?= $errors['date_end'] ?? "" ?>
            </span>
          </div>
        </div>
        <?php if (!empty($errors)): ?>
         <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
       <?php endif; ?>

       <button type="submit" name="send-lot" class="button">Добавить лот</button>
     </form>
  