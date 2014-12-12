require! {
  m: mithril
}

module.exports = (content) ->
  result = [
    m '.cards', [
      m 'header', m 'h1', content.title
      m '.hint' content.hint
      m '.card' content.card
      m 'a.next.fa.fa-arrow-circle-o-right'
    ]
  ]
