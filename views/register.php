    <h1>Registration page</h1>

    <form action ="" method="POST">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="firstname" class="form-label">Firstname</label>
                    <input type="text"
                           value="<?php echo $model->firstname ?>"
                           class="form-control <?php echo $model->hasErrors('firstname')? 'is-invalid':'' ?>" name ="firstname" id="firstname" >
                    <div class="invalid-feedback">
                        <?php echo $model->getFirstError('firstname') ?>
                        <p>Hello</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class=form-group">
                    <label for="lastname" class="form-label">Lastname</label>
                    <input type="text" value = "" class="form-control" name ="lastname" id="lastname" >

                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" >
        </div>
        <div class="mb-3">
            <label for="password_again" class="form-label">Password again</label>
            <input type="password" name="confirmPassword" class="form-control" id="password_again" >
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>