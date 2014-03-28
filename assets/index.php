<!DOCTYPE html>
<html>

    <?php include 'templates/html_head.php'; ?>

    <body>

        <?php /* HEADER */ ?>
        <?php include 'templates/nav_header.php'; ?>

        <div class="container-fluid">
                <div id="main-container" class="row">

                <?php /* BREADCRUMBS */ ?>
                <?php include 'templates/breadcrumbs.php'; ?>

                <?php /* GLOBAL MESSAGES */ ?>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php include 'templates/global_msg.php'; ?>
                        </div>
                    </div>
                </div>

                <div id="content-container" class="use-sidecontent sidecontent-at-left col-md-10 col-md-10 col-sm-10 col-xs-10">

                    <?php /* SIDECONTENT */ ?>
                    <?php include 'templates/sidecontent.php'; ?>

                    <?php /* MAIN CONTENT */ ?>
                    <?php include 'templates/maincontent.php'; ?>
                </div>

                <?php /* FIXED BUTTON BAR */ ?>
                <?php include 'templates/button_bar.php'; ?>

            </div>
        </div>

    </body>

</html>