// let userList=[];
console.log('link')

$(window).bind("beforeunload", function() {
    fnLogOut();
});

function selectUser(userData) {
    document.getElementsByClassName("allMessages")[0].innerHTML = "";

    if (unSelectMember() == 1) {
        //show loading message and remove old message

        enableHidden("loadingMessageDiv", false);

        // console.log(userData.split(":::"), 'userData');
        document.getElementById("userStatus").innerHTML=userData.split(":::")[4];
        var liInstance = document.getElementById(userData);
        var selectedUserName = liInstance.getElementsByTagName('h6')[0].innerHTML;
        var selectedUserImage = userData.split(":::")[2];
        // console.log(selectedUserName,'selectedUserName');
        if (liInstance.className == 'list-group-item currentUser') liInstance.setAttribute('class', 'list-group-item currentUserSelect');
        else liInstance.setAttribute('class', 'list-group-item currentUser');
        document.getElementById('receiverName').innerHTML = selectedUserName;
        document.getElementById('currentSelectedUser').src = "images/userImage/" + selectedUserImage;

        //set user data on para userInfo
        document.getElementById("userInfo").innerHTML = userData;
    }
}
function unSelectMember() {
    var ulInstance = document.getElementById('memberList');
    var liInstance = ulInstance.getElementsByTagName('li');
    // console.log(liInstance, liInstance.length);
    for (var i = 0; i < liInstance.length; i++) {
        liInstance[i].setAttribute('class', 'list-group-item currentUser');
    }
    return 1;
}
function sendMessage() {
    var userMessage = document.getElementById("userMessage").value.trim();
    if (userMessage.length == 0) return;

    console.log("sendMessage")
    enableHidden("sendMessage", true);
    enableHidden("waitingSpinner", false);


    var userData = document.getElementById("userInfo").innerHTML.split(":::");
    var recerverId = userData[0].trim();
    var groupId = userData[1].trim();
    var messageType = userData[3].trim();
    // var userStatus = (userData[4].length == 0) ? "Offline" : userData[4];
    // userStatus, 'userStatus'
    // console.log(userData,"userData");

    // console.log(userMessage, "userMessage", recerverId, 'receiverId', groupId, 'groupId', messageType, 'messageType');

    $.post('fetchMessageDetail.php', {
        'flagForSendMessage': "SEND",
        "recerverId": recerverId,
        "groupId": groupId,
        "messageType": messageType,
        "userMessage": userMessage
    }, function (data) {
        // console.log(data);
        document.getElementById("userMessage").value = "";
        enableHidden("sendMessage", false);
        enableHidden("waitingSpinner", true);
        loadSelectedUserMessage();
    });
}

function loadGroupUser() {
    document.getElementById("subMemberList").innerHTML = "";
    enableHidden("loadingUserMessage", false);
    // console.log('loadGroupUser');
    $.post('fetchMessageDetail.php', {
        'flagForUserDetail': true
    }, function (data) {
        // console.log(data,"loadGroupUser");
        document.getElementById("subMemberList").innerHTML = data;
        enableHidden("loadingUserMessage", true);
    });
}

function loadSelectedUserMessage() {
    console.log("loadSelectedUserMessage");

    var userData = document.getElementById("userInfo").innerHTML.split(":::");
    var recerverId = userData[0].trim();
    var groupId = userData[1].trim();
    var messageType = userData[3].trim();

    // console.log(recerverId, 'receiverId', groupId, 'groupId', messageType, 'messageType');

    $.post('fetchMessageDetail.php', {
        'flagForFetchMessage': "FETCH",
        "recerverId": recerverId,
        "groupId": groupId,
        "messageType": messageType,
    }, function (data) {
        var tempData = document.getElementById("userInfo").innerHTML.split(":::");
        var tempRecerverId = tempData[0].trim();

        // console.log(data, data.length,data.split(":%:%:")[1],tempRecerverId,"tempRecerverId",data.split(":%:%:")[1]==tempRecerverId);
        var tempMessage=data.split(":%:%:")[0];

        if (tempMessage.trim().length == 0) tempMessage = '<h4 class="text-center text-white">No message available</h4>';
        if (data.split(":%:%:")[1] == tempRecerverId) document.getElementsByClassName("allMessages")[0].innerHTML = tempMessage;
        else document.getElementsByClassName("allMessages")[0].innerHTML = '<div style="display: flex;justify-content: center;"><h5 class="text-white mt-3">Loading messages please wait..</h5><div class="spinner-border text-primary" role="status" style="margin-top: 15px;margin-left: 1%;"></div</div>';
            
        enableHidden("loadingMessageDiv", true);

        document.getElementsByClassName('MessageAreaBody')[0].scrollTo(0, parseInt(document.getElementsByClassName('MessageAreaBody')[0].scrollHeight));
    });

}

var myInterval = setInterval(loadSelectedUserMessage, 2000);
// clearInterval(myInterval);


function fnLogOut() { alert('browser closing'); }

