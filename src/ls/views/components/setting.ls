require! {
  m: mithril
}

module.exports = (title, content) ->
  result = [
    m '.settings', [
      m 'header', m 'h1', title
      m '.content', content
    ]
  ]
