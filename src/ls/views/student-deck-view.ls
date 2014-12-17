require! <[
  ./components/html5
  ./components/setting
]>

require! {m: mithril}

module.exports = (ctrl) ->
  decks = ctrl.decks or []
  result = html5(
    ['/css/app.css']
    setting \Decks, (decks.map ->
      active = if it.active then '.active' else ''
      message = if it.active
                then 'Deck in use by student.'
                else 'Deck not in use by student.'
      m \div [
        m "button#{active}" (
          onclick: ctrl.toggleDeck.bind ctrl, it.deckId
          title: message
        ), it.deckName
      ]
    )
  )
