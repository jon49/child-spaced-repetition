require! {
  m: mithril
}

module.exports = (styles, content) ->
  result = [
    m 'head', [
      m 'meta', (charset: 'UTF-8')
      m 'title', m.trust 'L&aelig;re | Spaced Repetition for Kids!'
      styles.map ->
        m 'link', (
          rel: 'stylesheet'
          href:  it
        )
    ]
    m 'body', content
  ]
