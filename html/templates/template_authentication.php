<?php
include_once("template_common.php");
?>

<?php function draw_login()
{
    /**
     * Draws the login page
     */ ?>
    <script src={{ asset('js/body_auth_prop.js') }} async></script>
    <section id="login" class="container d-flex flex-column align-items-center">
        <div id="form_div" class="col-sm-7">
            <h2 class="text-primary">Login in UConnect</h2>
            <form class="d-flex flex-column justify-content-center" method="post">
                <div class="form-group">
                    <input type="email" required class="row form-control" id="email" placeholder="Email">
                    <input type="password" required class="row form-control" id="password" placeholder="Password">
                </div>
                <button type="submit" class="row btn btn-primary" action="TO UPDATE" method="POST" style="color:whitesmoke">Login</button>
            </form>

            <footer style="margin: 0; margin-top: 1em; padding: 0;">
                <p class="row">Don't have an account? Then <a href="/register.php">&nbsp;REGISTER</a></p>
                <p class="row">Forgot your password? <a href="TO UPDATE">&nbsp;Recover password</a></p>
            </footer>
        </div>
    </section>

<?php } ?>


<?php function draw_signup()
{
    /**
     * Draws the sign up page
     */ ?>
    <script src="./js/body_auth_prop.js" async></script>
    <section id="register" class="container d-flex flex-column align-items-center">
        <div id="form_div" class="col-sm-7">
            <h2 class="text-primary">Register in UConnect</h2>
            <form class="d-flex flex-column justify-content-center" method="post">
                <div class="form-group">
                    <input type="text" required class="row form-control" id="name" required="required" placeholder="Name">
                    <input type="email" required class="row form-control" id="email" placeholder="Email">
                    <input type="password" required class="row form-control" id="password" placeholder="Password">
                    <input type="password" required class="row form-control" id="confpassword" placeholder="Confirm Password">
                    <input type="text" required class="row form-control" id="university" placeholder="University">
                </div>
                <div class="form-group">
                    <select class="form-control" required id="occupation" placeholder="Occupation">
                        <option>Choose an option:</option>
                        <option>Student</option>
                        <option>Teacher</option>
                        <option>Organization</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" action="TO UPDATE" method="POST">Register</button>
            </form>
            <footer>
                <p>Already have an account? Then <a href="/login.php" style="color: #ffa31a">LOGIN</a></p>
            </footer>
        </div>
    </section>




<?php } ?>