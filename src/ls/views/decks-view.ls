require! <[
  ./components/html5
  ./components/setting
]>

require! {m: mithril}

selectDeck = ->
  m \div [
    m \a, (
      config: m.route
      href:   "/app/decks/#{it.deckId}"
    ), [m \button it.deckName]
    m \button, {onclick: @editName.bind @, it.deckId}, \Edit
  ]

editDeck = (deck, ...cb)->
  cb_ = cb.0 ? @newName.bind @, deck.deckId
  m \div [
    m 'input#edit[type="text"]', (
      value: deck.deckName
      config: (e) !->
        e.onkeypress = ->
          | e.keyCode is 13 =>
            cb_
          | _ => #do nothing
        e.focus!
    )
    m \button, {onclick: cb_}, \Submit
  ]

newDeck = ->
  | @newDeck =>
    newDeck = {}
    newDeck.deckId = -1
    newDeck.deckName = 'Deck Name'
    editDeck.call @, newDeck, @createDeck.bind @
  | _ =>
    m \div.new-deck [
      m \a, (
        onclick: @editNewDeck.bind @
      ), [m \button, 'New Deck Name']
    ]

module.exports = (ctrl) ->
  decks = ctrl.decks or []
  result = html5(
    ['/css/app.css']
    setting 'Decks', (decks.map ->
      | it.edit =>
        editDeck.call ctrl, it
      | _ =>
        selectDeck.call ctrl, it
    ).concat newDeck.call ctrl
  )
