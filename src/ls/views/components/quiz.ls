require! {
  m: mithril
  r: ramda
}

module.exports = (content) ->
  result = [
    m 'main.cards', [
      m 'header', m 'h1', content.title
      m '.hint' content.hint
      m '.card' content.cardInfo.content
      m 'a.next.fa', (
        onclick: @nextCard.bind @, Date.now!
      ), \next
    ]
  ]
