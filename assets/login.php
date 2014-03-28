<!DOCTYPE html>
<html>

    <?php include 'templates/html_head.php'; ?>

    <body>
        <div class="container-fluid" id="login">

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">
                    <div id="logo" class="logo-lg-t"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">
                    <div class="panel">

                        <div class="panel-heading">
                            <b>Login</b>
                        </div>
                        <div class="panel-body">

                            <?php /* GLOBAL MESSAGES */ ?>
                            <?php include 'templates/global_msg.php'; ?>

                            <?php /* LOGIN FORM */ ?>
                                <form role="form">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" placeholder="Enter Username">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" placeholder="Password">
                                    </div>
                                    <div class="clearfix"></div>
                                    <a href="#" class="pull-left"><small>Forgot Passwort</small></a>
                                    <a class="btn btn-default pull-right">Login</a>
                                </form>
                            <?php /* /LOGIN FORM */ ?>

                            <?php /* LOST PASSWORD FORM
                            <form role="form">
                                <div class="form-group">
                                    <label for="email">E-Mail</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter Username">
                                </div>
                                <div class="clearfix"></div>

                                <a class="btn btn-default pull-right">Send</a>
                            </form> */ ?>
                            <?php /* /LOST PASSWORD FORM */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>