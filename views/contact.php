    <h1>Contact page</h1>

    <form action ="" method="POST">
        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" class="form-control" name ="subject" id="subject" aria-describedby="emailHelpSubject">
            <div id="emailHelpSubject" class="form-text">Please enter the subject.</div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="textbody" class="form-label">Description</label>
            <textarea type="text" name="body" class="form-control" id="textbody"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>