<?php
    include_once("template_common.php");
?>

<?php function draw_login()
{
    /**
     * Draws the login page
     */?>
    <body>
    <form>
        <div class="form-group">
            <input type="email" class="form-control" id="email" placeholder="Email">
            <br>
            <input type="password" class="form-control" id="password" placeholder="Password">


        </div>
        <button type="submit" class="btn btn-primary" action="TO UPDATE" method="POST">Login</button>

        
    </form>

    <footer>
            <p>Don't have an account? Then <a href="TO UPDATE">REGISTER</a></p>
            <p>Forgot your password? <a href="TO UPDATE">Recover password</a></p>

    </footer>

<?php } ?>


<?php function draw_signup()
{
    /**
     * Draws the sign up page
     */?>
      <body>
        <form>
            <div class="form-group">
                <input type="text" class="form-control" id="name" required="required" placeholder="Name">
                
                <input type="email" class="form-control" id="email" placeholder="Email">
                
                <input type="password" class="form-control" id="password" placeholder="Password">
                
                <input type="password" class="form-control" id="confpassword" placeholder="Confirm Password">
                
                <input type="text" class="form-control" id="university" placeholder="University">
                


            </div>
            <div class="form-group">
                <select class="form-control" id="occupation">
                <option>Choose an option:</option>
                <option>Student</option>
                <option>Teacher</option>
                <option>Organization</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" action="TO UPDATE" method="POST">Register</button>
        </form>
        </body>
        <footer>
            <p>Already have an account? Then <a href="TO UPDATE">LOGIN</a></p>
        </footer>
        
    


<?php } ?>