require! \./header

require! {
  m: mithril
}

module.exports = (styles, content) ->
  result =
    * m 'head',
      * m 'meta', (charset: 'UTF-8')
      * m 'title', m.trust 'L&aelig;re | Spaced Repetition for Kids!'
      * m \link (
        rel: \stylesheet
        href: \http://fonts.googleapis.com/css?family=Indie+Flower
        )
      * m \link (
        rel: \stylesheet
        href: \http://fonts.googleapis.com/css?family=Open+Sans
        )
      * styles.map ->
          m 'link', (
            rel: 'stylesheet'
            href:  it
          )
    * m 'body', [header!, content]
