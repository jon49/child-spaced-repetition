// Generated by LiveScript 1.2.0
var html5, setting, m, selectCard, editCard, newCard, slice$ = [].slice;
html5 = require('./components/html5');
setting = require('./components/setting');
m = require('mithril');
selectCard = function(it){
  return m('div', [
    m('a', {
      config: m.route,
      href: "/app/cards/" + it.cardId
    }, [m('button', it.content)]), m('button', {
      onclick: this.editName.bind(this, it.cardId)
    }, 'Edit')
  ]);
};
editCard = function(card){
  var cb, cb_, ref$;
  cb = slice$.call(arguments, 1);
  cb_ = (ref$ = cb[0]) != null
    ? ref$
    : this.newName.bind(this, card.cardId);
  return m('div', [
    m('input#edit[type="text"]', {
      value: card.content,
      config: function(e){
        e.onkeypress = function(){
          switch (false) {
          case e.keyCode !== 13:
            return cb_;
          }
        };
        e.focus();
      }
    }), m('button', {
      onclick: cb_
    }, 'Submit')
  ]);
};
newCard = function(){
  var newCard;
  switch (false) {
  case !this.newCard:
    newCard = {};
    newCard.cardId = -1;
    newCard.content = 'Card Name';
    return editCard.call(this, newCard, this.createCard.bind(this));
  default:
    return m('div.new-card', [m('a', {
      onclick: this.editNewCard.bind(this)
    }, [m('button', 'New Card Name')])]);
  }
};
module.exports = function(ctrl){
  var cards, result;
  cards = ctrl.cards || [];
  return result = html5(['/css/app.css'], setting('Cards', cards.map(function(it){
    switch (false) {
    case !it.edit:
      return editCard.call(ctrl, it);
    default:
      return selectCard.call(ctrl, it);
    }
  }).concat(newCard.call(ctrl))));
};