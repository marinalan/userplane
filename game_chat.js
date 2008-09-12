Ext.namespace('game', 'game.chat');
game.prepared = false;

Ext.apply(Ext.form.VTypes, {
  password: function(val, field) {
    if (field.initialPassField) {
      var pwd = Ext.getCmp(field.initialPassField);
      return (val == pwd.getValue());
    }
    return true;
  },
  
  passwordText: 'Passwords do not match'
});

game.show_login_form = function(){
  var loginf = new Ext.FormPanel({
    id:  'login_form',
    url: 'login.php',
    labelWidth: 125,
    frame: true,
    title: 'Login',
    bodyStyle:'padding:5px 5px 0',
    width: 350,
    cls: 'middle',
    defaults: {
      width: 175,
    },
    defaultType: 'textfield',
    items: [{
      fieldLabel: 'Username',
      name: 'username',
      id: 'username'
    },{
      fieldLabel: 'Password',
      name: 'pass',
      id: 'pass' // id of the initial password field
    }],

    buttons: [{
      text: 'Login'
    }]    
    
  });

  loginf.render('logincnt');
   Ext.get('register').on('click', function(){
       game.chat.step = 'sign_on';
       Ext.destroy(Ext.getCmp('login_form'));
       Ext.get('login').replaceClass('step_active', 'step');

       game.show_registration_form();
  });

  Ext.get('login').replaceClass('step', 'step_active');
};

game.show_registration_form = function(){
  var signonf = new Ext.FormPanel({
      labelWidth: 110, // label settings here cascade unless overridden
      url:'save_user.php',
      id: 'register_form',
      frame:true,
      title: 'Registration',
      bodyStyle:'padding:5px 5px 0',
      cls: 'middle',
      width: 400,
      defaults: {width: 230},
      defaultType: 'textfield',

      items: [{
              fieldLabel: 'Username',
              name: 'username',
              allowBlank:false
          },{
              fieldLabel: 'Password',
              name: 'pass',
              id: 'pass',
              allowBlank:false,
              inputType: 'password'
          },{
              fieldLabel: 'Confirm Password',
              name: 'pass-cfrm',
              inputType: 'password',
              /*vtype: 'password',*/
              initialPassField: 'pass' // id of the initial password field
          },{
              fieldLabel: 'Email',
              name: 'email',
              allowBlank:false,
              vtype:'email'
          },{
              fieldLabel: 'Phone',
              name: 'phone'
          },{
              fieldLabel: 'Full Name',
              name: 'name'
          },{
              fieldLabel: 'Location',
              name: 'location'
          }, new Ext.form.DateField({
              fieldLabel: 'Date of Birth',
              name: 'birthday',
              width:190,
              allowBlank:false
          })
      ],

      buttons: [{
          text: 'Save',
          handler: function(){
            signonf.getForm().submit({
              url: 'save_user.php',
              /*waitMsg:'Saving Data...',*/
              success: function(response){
                game.chat.step = 'pick_game';
                Ext.destroy(Ext.getCmp('register_form'));
                Ext.get('login').replaceClass('step_active', 'step');

                game.show_pick_game_form();
              }});
          }
      },{
          text: 'Reset',
          handler: function(){
          }
      }]
  });

  signonf.render('sign_on');
  Ext.get('sign_on').replaceClass('step', 'step_active');
};

game.show_pick_game_form = function(){
  if (!game.prepared){  
    var ds = new Ext.data.Store({  
      proxy: new Ext.data.HttpProxy({  
          // this json data contains only employees where active is 'true'  
          url: 'games_combobox_data.php'  
      }),  
      reader: new Ext.data.JsonReader({
        idProperty:'game_id',
        root: 'data', 
        totalProperty: 'results',
        fields: [
            {name: 'game_id', type: 'int'},
            {name: 'game_name', type: 'string'},
            {name: 'rules', type: 'string'},
            {name: 'min_players', type: 'int'},
            {name: 'max_players', type: 'int'}
        ]
      }),  
      remoteSort: false  
    });
    ds.load();

    var comboWithTooltip = new Ext.form.ComboBox({
        tpl: '<tpl for="."><div ext:qtip="{min_players}-{max_players}. {rules}" class="x-combo-list-item">{game_name}</div></tpl>',
        store: ds,
        id: 'chosen_game',
        listClass: 'games_list',
        listWidth: 250, 
        valueField: 'game_id',
        displayField:'game_name',
        hiddenName: 'game_id',
        typeAhead: true,
        mode: 'remote',
        triggerAction: 'all',
        emptyText:'Select a game...',
        selectOnFocus:true,
        applyTo: 'games-with-qtip',
        listeners: {
          select:{
             fn: function(combo, value){
               game.id = value.data.game_id;
               game.name = value.data.game_name;

               JoinGameBtn.show();
             }
          }/*,
          keydown:{
             fn: function(combo, value){
               JoinGameBtn.hide();
             }
          }*/
        }
    });

    var request_room = function(){
      var conn = new Ext.data.Connection();
      conn.request({
        url: 'game_assign_room.php',
        method: 'POST',
        params: {game_id: game.id, game_name: game.name},
        success: function(responseObject) {
          if (window.console){
            console.log(responseObject.responseText);
          }
          Ext.get('pick_game').replaceClass('step_active', 'step');
          show_chat();
        },
        failure: function() {
          Ext.Msg.alert('Status', 'Unable to request room. Please try again later.');
        }
      });
   };

   var JoinGameBtn = new Ext.Button({
      text: 'Join this Game',
      renderTo: 'start_btn',
      hidden: true,
      style: {display: "inline"},
      handler: function(){
        request_room();
      }
   });

   game.prepared = true;
  }
    
  Ext.get('pick_game').replaceClass('step', 'step_active');
};

Ext.onReady(function(){

    Ext.QuickTips.init();
    // turn on validation errors beside the field globally
    Ext.form.Field.prototype.msgTarget = 'side';

    if (game.chat.step == 'login') {
      game.show_login_form();
    }
    else if (game.chat.step == 'login') {
      game.show_registration_form();      
    }
    else if (game.chat.step == 'pick_game') {
      game.show_pick_game_form();
    }
    else {
      show_chat();
    }

});
