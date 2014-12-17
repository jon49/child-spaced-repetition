// Generated by LiveScript 1.2.0
var html5, setting, m;
html5 = require('./components/html5');
setting = require('./components/setting');
m = require('mithril');
module.exports = function(ctrl){
  var decks, result;
  decks = ctrl.decks || [];
  return result = html5(['/css/app.css'], setting('Decks', decks.map(function(it){
    var active, message;
    active = it.active ? '.active' : '';
    message = it.active ? 'Deck in use by student.' : 'Deck not in use by student.';
    return m('div', [m("button" + active, {
      onclick: ctrl.toggleDeck.bind(ctrl, it.deckId),
      title: message
    }, it.deckName)]);
  })));
};