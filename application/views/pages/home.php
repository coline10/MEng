<div class="row"><div class="col-xs-12 col-sm-6"><h2 class="time"><small class="pull-left"><?php echo $cDate ?></small></h2></div><div class="col-xs-12 col-sm-6"><h2 class="time"><small class="visible-xs pull-left"><?php echo $cTimespan ?></small><small class="hidden-xs pull-right"><?php echo $cTimespan ?></small></h2></div></div>
<!--HEADING-->
<div class="navbar" style="min-height: 30px; margin-bottom:10px;"><div id="category-dropdown" class="dropdown pull-right">
<button class="btn dropdown-toggle" type="button" id="category-dropdown-btn" data-toggle="dropdown">Categories<span class="caret"></span></button><ul class="dropdown-menu multi-select" role="menu"><?php foreach($categories as $cat): ?><li role="presentation" id="<?php echo strtolower(str_replace(' ', '_', $cat['category']))?> " class="selected"><a style="color:<?php echo $cat['color'] ?>" data-category-id='<?php echo $cat['category_id'] ?>'><?php echo $cat['category'] ?></a></li><?php endforeach; ?></ul></div>
<button class="btn dropdown-toggle" type="button" id="category-dropdown-btn" data-toggle="dropdown">Rooms<span class="caret"></span></button><ul class="dropdown-menu multi-select" role="menu"><?php foreach($rooms as $room): ?><li role="presentation" id="<?php echo strtolower(str_replace(' ', '_', $room['room']))?> "class="selected"><a data-category-id='<?php echo $cat['category_id'] ?>' ><?php echo $room['room'] ?></a></li><?php endforeach; ?></ul></div>
<!--BODY-->
<div class="row list" style="height:400px"><!--ROW-->
<?php include 'class-list.php'; ?>
</div><!--/ROW-->
