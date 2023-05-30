var groupName = '';
var firstName = '';
var lastName = '';
var loginName = '';
var loginPassword = '';
var hostName = '';
var userName = '';
var dbPassWord = '';
var accountType = '';
var flagForValdCredential = false;
var userMasterData = [];
var userType = '';
var oldUserImageName = '';
var userImage = '';
var flagImageUpload = false;


function loadDataInAccount() {
    // console.log(userMasterData, 'userData');
    console.log(userMasterData[0].accountInfo.split(':::')[0], userMasterData[0].accountInfo.split(':::')[0] == 'group')
    if (userMasterData[0].accountInfo.split(':::')[0] == 'group') {
        document.getElementById('groupSection').hidden = false;
        document.getElementById('groupName').value = userMasterData[0].accountInfo.split(':::')[1];
    }

    oldUserImageName=userMasterData[0].imageName;
    userType = document.getElementById('userType').innerHTML = userMasterData[0].userType;
    document.getElementById('profileImage').src = document.getElementById('imagePreview').src = "images/userImage/" + oldUserImageName;

    hostName = userMasterData[0].hostName;
    userName = userMasterData[0].userName;
    dbPassWord = userMasterData[0].databasePassword;
    if (userMasterData[0].userType == 'OWNER') {
        document.getElementById('userType').setAttribute('style', 'color:orange;border-top: 2px solid;border-bottom: 2px solid;');
        document.getElementById('hostName').value = hostName;
        document.getElementById('userName').value = userName;
        document.getElementById('dbPassWord').value = dbPassWord;
    }

    else if (userMasterData[0].userType == 'EDITOR') document.getElementById('userType').setAttribute('style', 'color:yellow;border-top: 2px solid;border-bottom: 2px solid;');
    else document.getElementById('userType').setAttribute('style', 'color:white;border-top: 2px solid;border-bottom: 2px solid;');

    document.getElementById('firstName').value = userMasterData[0].firstname;
    document.getElementById('lastName').value = userMasterData[0].lastname;
    document.getElementById('loginName').value = userMasterData[0].loginName;
    document.getElementById('loginPassword').value = userMasterData[0].loginPassword;

}
function enableEdit() {
    enableAllInput();
    document.getElementsByClassName('viewOnly')[0].hidden = true;
    document.getElementById('editButton').hidden = true;
    document.getElementById('updateButton').hidden = false;
    document.getElementsByClassName('editView')[0].hidden = false;
    document.getElementsByClassName('editView')[1].hidden = false;
}


function updateAcount(accountType) {

    console.log(accountType, 'accountType', userType, 'userType');
    disabledButton('updateButton', true);
    if (accountType == 'group') groupName = document.getElementById('groupName').value.trim();
    console.log(accountType == 'group', groupName)
    firstName = document.getElementById('firstName').value.trim();
    lastName = document.getElementById('lastName').value.trim();
    loginName = document.getElementById('loginName').value.trim();
    loginPassword = document.getElementById('loginPassword').value.trim();
    userImage = document.getElementById('userImage');


    if (userImage.files == undefined) flagImageUpload = false;
    else if (userImage.files.length > 0){
        userImage=userImage.files[0];
        flagImageUpload = true;
    } 


    if (userType == 'OWNER') {
        hostName = document.getElementById("hostName").value.trim();
        userName = document.getElementById("userName").value.trim();
        dbPassWord = document.getElementById("dbPassWord").value.trim();

        if (validateDetails() == 0) {
            disabledButton('updateButton', false);
            return 0;
        }
    }



    // console.log(hostName, userName, dbPassWord);
    document.getElementById("success").innerHTML = "Updating Account Please Wait....";


    $.post('checkConnection.php', {
        'hostName': hostName,
        'userName': userName,
        'dbPassWord': dbPassWord
    }, function (data) {
        console.log(data);

        if (data == 'validCredential') {
            flagForValdCredential = true;

            if (userType == 'OWNER') {
                showBgRed('hostName', false);
                showBgRed('userName', false);
                showBgRed('dbPassWord', false);
            }
            showErrorMessage("error", "");


            formData = new FormData();
            formData.append("flagforUpdateAccount", "UPDATE");
            formData.append("accountType", accountType);
            formData.append("groupName", groupName);
            formData.append("firstName", firstName);
            formData.append("lastName", lastName);
            formData.append("loginName", loginName);
            formData.append("loginPassword", loginPassword);
            formData.append("hostName", hostName);
            formData.append("userName", userName);
            formData.append("dbPassWord", dbPassWord);
            formData.append("userImage", userImage);
            formData.append("oldUserImageName", oldUserImageName);
            formData.append("flagImageUpload", flagImageUpload);

            $.ajax({
                url: "saveCreateAccount.php",
                type: "POST",
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    // console.log(data);
                    if (data == 'true') {
                        document.getElementById("success").innerHTML = "Account Updated Successfully..";
                        location.reload();
                    }
                }
            });
            // $.post('saveCreateAccount.php', {
            //     'flagforUpdateAccount': 'UPDATE',
            //     'accountType': accountType,
            //     'groupName': groupName,
            //     'firstName': firstName,
            //     'lastName': lastName,
            //     'loginName': loginName,
            //     'loginPassword': loginPassword,
            //     'hostName': hostName,
            //     'userName': userName,
            //     'dbPassWord': dbPassWord
            // }, function (data) {
            //     console.log(data);
            //     if (data == 'true') {
            //         document.getElementById("success").innerHTML = "Account Updated Successfully..";
            //         location.reload();
            //     }
            // });
        } else {
            if (userType == 'OWNER') {
                showBgRed('hostName', true);
                showBgRed('userName', true);
                showBgRed('dbPassWord', true);
            }
            document.getElementById("success").innerHTML = "";

            showErrorMessage("error", "Invalid Database Credential.");
            disabledButton('updateButton', false);
            return 0;
        }
    });

}

function validateDetails() {
    accountType = userMasterData[0].accountInfo.split(':::')[0];
    console.log(accountType);
    if (accountType == 'group' && groupName.length == 0) {
        showBgRed('groupName', true);
        showErrorMessage("error", "Group Name Can Not Be Blank.");
        return 0;
    } else {
        showBgRed('groupName', false);
        showErrorMessage("error", "");
    }

    if (firstName == "") {
        showBgRed('firstName', true);
        showErrorMessage("error", "First Name Can Not Be Blank.");
        return 0;
    } else {
        showBgRed('firstName', false);
        showErrorMessage("error", "");
    }
    if (lastName == "") {
        showBgRed('lastName', true);
        showErrorMessage("error", "Last Name Can Not Be Blank.");
        return 0;
    } else {
        showBgRed('lastName', false);
        showErrorMessage("error", "");
    }
    if (loginName == "") {
        showBgRed('loginName', true);
        showErrorMessage("error", "Login Name Can Not Be Blank.");
        return 0;
    } else {
        showBgRed('loginName', false);
        showErrorMessage("error", "");
    }
    if (validateEmail(loginName) == false) {
        showBgRed('loginName', true);
        showErrorMessage("error", "Login Name Not In Correct Formate.");
        return 0;
    }

    if (loginPassword.length < 6) {
        showBgRed('loginPassword', true);
        showErrorMessage("error", "Login Password Length Should Be Atleast 6 Digit.");
        return 0;
    } else {
        showBgRed('loginPassword', false);
        showErrorMessage("error", "");
    }

    if (userType == 'OWNER') {
        if (hostName == "") {
            showBgRed('hostName', true);
            showErrorMessage("error", "Host Name Can Not Be Blank.");
            return 0;
        } else {
            showBgRed('hostName', false);
            showErrorMessage("error", "");
        }
        if (userName == "") {
            showBgRed('userName', true);
            showErrorMessage("error", "User Name Can Not Be Blank.");
            return 0;
        } else {
            showBgRed('userName', false);
            showErrorMessage("error", "");
        }
        if (dbPassWord == "") {
            showBgRed('dbPassWord', true);
            showErrorMessage("error", "Database Password Can Not Be Blank.");
            return 0;
        } else {
            showBgRed('dbPassWord', false);
            showErrorMessage("error", "");
        }
    }

    return 1;
}
