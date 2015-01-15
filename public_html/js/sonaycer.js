var basis = {
    current: window.location.href,
    host: $('body').data('sonaycer-host'),
    path: $('body').data('sonaycer-path'),
    url: 'http://' + $('body').data('sonaycer-host') + '/' + $('body').data('sonaycer-path')
};
var startAction = $('#sonaycer-start-container').data('sonaycer-start-action');
var time = 0;

var stpwtch=function(){var startAt=0;var lapTime=0;var now=function(){return(new Date()).getTime()};this.start=function(){startAt=startAt?startAt:now()};this.stop=function(){lapTime=startAt?lapTime+now()-startAt:lapTime;startAt=0};this.reset=function(){lapTime=startAt=0};this.time=function(){return lapTime+(startAt?now()-startAt:0)}};var x=new stpwtch();var $time;var clocktimer;function pad(num,size){var s="0000"+num;return s.substr(s.length-size)}function formatTime(time){var h=m=s=ms=0;var newTime='';h=Math.floor(time/(60*60*1000));time=time%(60*60*1000);m=Math.floor(time/(60*1000));time=time%(60*1000);s=Math.floor(time/1000);ms=time%1000;newTime=pad(h,2)+':'+pad(m,2)+':'+pad(s,2)+':'+pad(ms,3);return newTime}function show(){$time=document.getElementById('time');update()}function update(){$time.innerHTML=formatTime(x.time())}function start(){clocktimer=setInterval("update()",1);x.start()}function stop(){x.stop();clearInterval(clocktimer)}function reset(){stop();x.reset();update()}

//create a stopwatch object in 'stopwatch'
stopwatch = new stpwtch();

function preInit() {
    stopwatch.start();
    $('.loading').append('<div class="bullet"></div><div class="bullet"></div><div class="bullet"></div><div class="bullet"></div>');
    
    switch(startAction) {
        case 'compose':
            $('#sonaycer-start-text').text('A new draft is prepairing...');
            break;
        case 'folder':
            $('#sonaycer-start-text').text('Your mail is loading... To view a folder');
            break;
        case 'folderview':
        default:
            $('#sonaycer-start-text').text('Your mail is loading...');
    }
    
    $('#sonaycer-start-col h1, #sonaycer-start-col div').hide().slideDown(1500);
    
    init();
}
function init() {
    navigator.registerProtocolHandler("mailto",basis.url + '/compose?mailto_data=%s',"Sonaycer MBox");
    
    actions.updateUser();
    
    switch(startAction) {
        case 'compose':
            composeData = $('#sonaycer-start-container').data('sonaycer-compose-data');
            actions.disableComposeButton();
            break;
        case 'folderview':
            actions.folder.loadView('INBOX');
            actions.registerHandlerForComposeButton();
            break;
        case 'folder':
            actions.folder.loadView($('#sonaycer-start-container').data('sonaycer-action-folder'))
            actions.registerHandlerForComposeButton();
    }
    
    stopwatch.stop();
    if(stopwatch.time() <= 2000) {
        setTimeout(postInit, 2000);
    } else {
        postInit();
    }
    stopwatch.reset();
}
function postInit() {
    
    
    $('#sonaycer-main-container').hide().removeClass('hidden');
    $('#sonaycer-start-container').slideUp();
    $('#sonaycer-main-container').slideDown();
}
var actions = {
    _var: {
        currentToken: '',
        currentFolder: 'INBOX'
    },
    folder: {
        loadView: function(folderId) {
            
        },
        newFolder: function(foldername) {
            
            //on success
            actions.folder.loadView(folderId)
        },
        deleteFolder: function(folderId){
            
        },
        renameFolder: function(folderId, newName) {
            
        },
        getLastUsedView: function() {
            return this._var.currentFolder;
        }
    },
    compose: {
        newDraft: function() {
            actions.disableComposeButton();
        },
        openDraft: function(draftId) {
            
        },
        saveDraft: function() {
            $.ajax(basis.url + '/ajax/saveDraft', {
                async: false,
                type: 'POST',
                data: {
                    contentHtml: '',
                    draftId: '',
                    time: ''
                }
            })
            .done(function(data) {
                if (data.success) {
                    actions.registerHandlerForComposeButton();
                    actions.folder.loadView(actions.folder.getLastUsedView());
                } else {
                    
                }
            })
            .fail(function() {
                
            });
        },
        send: function() {
            
        }
    },
    updateUser: function() {
        $.ajax(basis.url + '/ajax/getSession', {
            async: false
        })
        .done(function(data) {
            if (data.signedIn) {
                actions.setToken;
                return true;
            } else {
                window.location = basis.url + '/auth?return=' + basis64_encode(basis.current);
            }
        })
        .fail(function() {
            return false;
        });
    },
    setToken: function(token) {
        this._var.currentToken = token;
    },
    getToken: function() {
        return this._var.currentToken;
    },
    registerHandlerForComposeButton: function() {
        $('#sonaycer-compose-button').on('click', function() {
            actions.compose.newDraft();
        });
    },
    disableComposeButton: function() {
        $('#sonaycer-compose-button').addClass('disabled');
        $('#sonaycer-compose-button').on('click', function() {});
    }
};
function basis64_encode(data){var b64='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';var o1,o2,o3,h1,h2,h3,h4,bits,i=0,ac=0,enc='',tmp_arr=[];if(!data){return data}do{o1=data.charCodeAt(i++);o2=data.charCodeAt(i++);o3=data.charCodeAt(i++);bits=o1<<16|o2<<8|o3;h1=bits>>18&0x3f;h2=bits>>12&0x3f;h3=bits>>6&0x3f;h4=bits&0x3f;tmp_arr[ac++]=b64.charAt(h1)+b64.charAt(h2)+b64.charAt(h3)+b64.charAt(h4)}while(i<data.length);enc=tmp_arr.join('');var r=data.length%3;return(r?enc.slice(0,r-3):enc)+'==='.slice(r||3);}

$(document).ready(preInit);