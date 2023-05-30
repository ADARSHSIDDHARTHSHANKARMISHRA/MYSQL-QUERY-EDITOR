let firstName = '', lastName = '', userType = '', loginId = '', loginPassword = '';
console.log('linkl');
var masterEArray=[];
function addMember() {
    // console.log('Called');
    document.getElementById('successMessage').innerHTML = "Adding member detail.Please wait...";

    firstName = document.getElementById('firstName').value.trim();
    lastName = document.getElementById('lastName').value.trim();
    userType = document.getElementById('userType').value.trim();
    loginId = document.getElementById('loginId').value.trim();
    loginPassword = document.getElementById('loginPassword').value.trim();

    console.log(firstName, lastName, userType, loginId, loginPassword);

    if (validateData() == false) {
        document.getElementById('successMessage').innerHTML = "";
        return;
    }

    $.post('allMemberSave.php', {
        'flagForSave': 'flagForSave',
        'firstName': firstName,
        'lastName': lastName,
        'userType': userType,
        'loginId': loginId,
        'loginPassword': loginPassword
    }, function (data) {
        data = data.trim();
        console.log(data);
        if (data == 'true') {
            document.getElementById('successMessage').innerHTML = "Detail SuccesFully Added.";
            location.reload();
        }
    });
}
function validateData() {
    if (firstName.length <= 0) {
        showErrorMessage('errorMessage', "First Name Can't Be Blank.");
        showBgRed('firstName', true);
        return false;
    } else {
        showErrorMessage('errorMessage', "");
        showBgRed('firstName', false);
    }

    if (lastName.length <= 0) {
        showErrorMessage('errorMessage', "Last Name Can't Be Blank.");
        showBgRed('lastName', true);
        return false;
    } else {
        showErrorMessage('errorMessage', "");
        showBgRed('lastName', false);
    }

    if (userType.length <= 0) {
        showErrorMessage('errorMessage', "Please Select User Type.");
        showBgRed('userType', true);
        return false;
    } else {
        showErrorMessage('errorMessage', "");
        showBgRed('userType', false);
    }

    if (loginId.length <= 0) {
        showErrorMessage('errorMessage', "Login Id Can't Be Blank.");
        showBgRed('loginId', true);
        return false;
    } else {
        showErrorMessage('errorMessage', "");
        showBgRed('loginId', false);
    }
    if (validateEmail(loginId) == false) {
        showErrorMessage('errorMessage', "Login Id Not In Correct Format.Please Correct It..");
        showBgRed('loginId', true);
        return false;
    } else {
        showErrorMessage('errorMessage', "");
        showBgRed('loginId', false);
    }
    if (checkDuplicateEmail(loginId)==true) {
        showBgRed('loginId', true);
        showErrorMessage("errorMessage", "Duplicate Email/Login Id Found.Please Change It");
        return false;
    } else {
        showBgRed('loginId', false);
        showErrorMessage("errorMessage", "");
    }

    if (loginPassword.length <= 0) {
        showErrorMessage('errorMessage', "Login Password Can't Be Blank.");
        showBgRed('loginPassword', true);
        return false;
    } else {
        showErrorMessage('errorMessage', "");
        showBgRed('loginPassword', false);
    }

    return true;
}
function checkDuplicateEmail(userEmail) {
    userEmail=userEmail.trim();
    for(var i=0;i<masterEArray.length;i++){
        if(userEmail==masterEArray[i].loginName){
            return true;
        }
    }
    return false;
}

function deleteMember(memberInfo) {
    var memberId = memberInfo.split(':%:')[0];
    var fullName = memberInfo.split(':%:')[1];

    if (confirm('Are you sure want to delete member ' + fullName)) {
        $.post('allMemberSave.php', {
            'flagForDelete': 'flagForDelete',
            'userId': memberId
        }, function (data) {
            data = data.trim();
            console.log(data);
            if (data == 'true') {
                location.reload();
            }
        });
    }
}