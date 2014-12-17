require! <[
  ./components/html5
  ./components/setting
]>

require! {m: mithril}

selectCard = ->
  m \div [
    m \a, (
      config: m.route
      href:   "/app/cards/#{it.cardId}"
    ), [m \button it.content]
    m \button, {onclick: @editName.bind @, it.cardId}, \Edit
  ]

editCard = (card, ...cb)->
  cb_ = cb.0 ? @newName.bind @, card.cardId
  m \div [
    m 'input#edit[type="text"]', (
      value: card.content
      config: (e) !->
        e.onkeypress = ->
          | e.keyCode is 13 =>
            cb_
          | _ => #do nothing
        e.focus!
    )
    m \button, {onclick: cb_}, \Submit
  ]

newCard = ->
  | @newCard =>
    newCard = {}
    newCard.cardId = -1
    newCard.content = 'Card Name'
    editCard.call @, newCard, @createCard.bind @
  | _ =>
    m \div.new-card [
      m \a, (
        onclick: @editNewCard.bind @
      ), [m \button, 'New Card Name']
    ]

module.exports = (ctrl) ->
  cards = ctrl.cards or []
  result = html5(
    ['/css/app.css']
    setting 'Cards', (cards.map ->
      | it.edit =>
        editCard.call ctrl, it
      | _ =>
        selectCard.call ctrl, it
    ).concat newCard.call ctrl
  )
