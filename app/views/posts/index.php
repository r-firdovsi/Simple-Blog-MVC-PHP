<?php require APPROOT . '/views/inc/header.php'; ?>
    <?php flash('post_message'); ?>
    <div class="row mt-2">
        <div class="col-md-6">
        <h1>Post</h1>
        </div>
        <div class="col-md-6">
            <a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-primary pull-right mt-2">
            <i class="fa fa-pencil"></i> Add Post
            </a>
        </div>
    </div>
    <div class="row mt-4">
    <?php foreach($data['posts'] as $post) : ?>
        <div class="col-md-4">
        <img style="width: 100%; height: 40%;" src="<?php echo URLROOT; ?>/public/img/<?php echo $post->image; ?>" class="card-img-top">
        <div class="card card-body mb-3">
            <h4 class="card-title"><?php echo $post->title; ?></h4>
            <div class="bg-light p-2 mb-3">
                Written by <?php echo $post->name; ?> on <?php echo $post->postCreated; ?>
            </div>
            <p class="card-text"><?php echo $post->body; ?></p>
            <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark">More</a>
        </div></div>
    <?php endforeach; ?>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>