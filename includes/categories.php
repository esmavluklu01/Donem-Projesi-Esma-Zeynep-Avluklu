
            <div class="area target">
               <h3>Categories</h3>
               <?php
               // Kategorileri 5 ile sınırla
               $categories_to_display = array_slice($categories, 0, 5);
               foreach ($categories_to_display as $category): ?>
                   <a href="categoriesBlog.php?id=<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></a>
               <?php endforeach; ?>

               <?php if (count($categories) > 5): ?>
                   <a href="categories.php" class="show-more">Show More</a>
               <?php endif; ?>
            </div>