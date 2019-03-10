    <nav class="nav">
     <ul class="nav__list container">
            <?php foreach ($categories as $category): ?>
              <li class="nav__item">
                <a href="all_lots.php"><?= htmlspecialchars($category['category_name']) ?></a>
              </li>
            <?php endforeach ?>
          </ul>
    </nav>
    <form class="form container  <?= isset($errors) ? "form--invalid" : "" ?> " action="sign_up.php" method="post" enctype="multipart/form-data"> 
      <h2>Регистрация нового аккаунта</h2>
      <div class="form__item <?= isset($errors['email']) ? "form__item--invalid" : "" ?>">
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''?>" placeholder="Введите e-mail" required>
        <span class="form__error">
              <?= $errors['email'] ?? "" ?>
        </span>

      </div>
      <div class="form__item <?= isset($errors['password']) ? "form__item--invalid" : "" ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" value="" placeholder="Введите пароль" required>
        <span class="form__error">
              <?= $errors['password'] ?? "" ?>
        </span>
      </div>
      <div class="form__item <?= isset($errors['name']) ? "form__item--invalid" : "" ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''?>" placeholder="Введите имя" required>
        <span class="form__error">
              <?= $errors['name'] ?? "" ?>
        </span>
      </div>
      <div class="form__item <?= isset($errors['contact']) ? "form__item--invalid" : "" ?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="contact" placeholder="Напишите как с вами связаться" required
        ><?= isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''?></textarea>
        <span class="form__error">
              <?= $errors['contact'] ?? "" ?>
        </span>
      </div>
      <div class="form__item form__item--file form__item--last">
        <label>Аватар</label>
        <div class="preview">
          <button class="preview__remove" type="button">x</button>
          <div class="preview__img">
            <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
          </div>
        </div>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" name="image" id="photo2" value="">
          <label for="photo2">
            <span>+ Добавить</span>
          </label>
           <span class="form__error">
              <?= $errors['image'] ?? "" ?>
        </span>
        </div>
      </div>
      <?php if (!empty($errors)): ?>
         <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
       <?php endif; ?>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
 