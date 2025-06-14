<p align="center">
<!--   <img src="YOUR_LOGO_IMAGE_URL" alt="Logo" width="200"/> -->
</p>

# PAYROLL MANAGEMENT SYSTEM 

ITEC 80A - Simple Project

## Features

* Admin manage employee salary.
* Login two types of account (Admin and Employee) | Auto filter login.
* Forgot Password with OTP / recovery code sent through email.
* Export PDF files for Reports as well as list of employee, etc.
* Integrated system - Mailer (API).

## Help And Guide ( Git Commands )

### Initialize repository

* **git init**
    * (one time only | Pag naka pag init kana di mo na kilangan pa ulit mag init pa).
* **git branch -m main**
    * (Change from master to main).
* **git remote add origin "--GIT REPOSITORY LINK--"**
    * (one time only | Pag naka pag remote add kana di mo na kilangan pa ulit mag remote).
 
### Retrieve Data from Github Repository

* **git pull origin main**
    * (retrieve data from git repository).
      
### Upload New Update to Github Repository

* **git add .**
    * (Add all data in folder to repository).
* **git commit -m "--COMMENT--"**
    * (Save and Add comment in file).
* **git push origin main**
    * (Upload the file to GIT repository ).
<br/>

### NOTE (FOR CONTRIBUTORS)
* Always back up your files before pulling from the repository.
    * To prevent lost of your new updates.

## Authors

Contributors
* [Jj](https://github.com/jjharvey00)
* [Rod](https://github.com/Rodney22-blimp)
* [Yrria](https://github.com/Yrria)
* [DH-Nyel](https://github.com/karinaonly)
* [Jps](https://github.com/JPMacaspac)
* [Nix](https://github.com/6nix)


## Purpose

This project is for educational purposes only. It is used to fulfill the requirements for a course subject.
<br/>
ITEC 80A - HUMAN COMPUTER INTERACTION 1.

<br>
<br>
<br>
git init                             # Initialize a new Git repository locally
git branch -m main                   # Rename the default branch to 'main'
git remote add origin "Link"         # Add the remote repository URL (replace "Link" with your repo URL)
git add .                           # Stage all files for the initial commit
git commit -m "Initial commit"      # Commit the staged files with a message
git push -u origin main             # Push initial commit to remote 'main' branch and set upstream tracking

## Initialize and set up remote and branches (if not already done)
git init                            # Initialize repo (skip if already done)
git branch -m main                  # Rename default branch to 'main'
git remote add origin "Link"         # Add remote repository URL
git pull origin main                # Pull remote main branch changes (only if remote has commits)
git checkout main                  # Switch to main branch
git checkout -b new-branch-name    # Create and switch to a new feature branch
git push -u origin new-branch-name  # Push new branch to remote and set upstream tracking

## Pull and merge main branch changes into feature branch workflow
git checkout your-branch-name       # Switch to your feature branch
git pull origin main               # Update local main branch with remote changes
git add .                           # Stage all changes for commit
git push origin your-branch-name    # Push the branch to the remote

## Push in branch and merge in main branch
git checkout your-branch-name       # Switch to your feature branch
git add .                           # Stage all changes for commit
git commit -m "Resolved merge conflicts"  # Commit with a message
git push origin your-branch-name    # Push the branch to the remote
git fetch origin                    # Fetch latest changes from remote

git checkout main                   # Switch to the main branch
git pull origin main                # Make sure your local main is up to date
git merge your-branch-name          # Merge your feature branch into main
git push origin main                # Push the updated main branch to the remote

# To know what branch you are on
git branch
# Check branch Status
git status


# (optional if merging does not work in main into feature branch)
git checkout branch-name
git fetch origin
git merge origin/main

## Acknowledgments

Inspiration, code snippets, reference etc.
  
