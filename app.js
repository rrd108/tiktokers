let tiktokers = []
const min = {}
const max = {}

const statsBody = document.getElementById('stats')
const thead = document.getElementById('thead')

thead.addEventListener('click', e => {
  if (e.target.nodeName == 'BUTTON') {
    const prop = e.target.id.replace('orderBy_', '')
    if (
      !sessionStorage.getItem('order') ||
      sessionStorage.getItem('order') == 'desc'
    ) {
      tiktokers.sort((a, b) => b[prop] - a[prop])
      sessionStorage.setItem('order', 'asc')
    } else {
      tiktokers.sort((a, b) => a[prop] - b[prop])
      sessionStorage.setItem('order', 'desc')
    }
    generateTable()
  }
})

stats.addEventListener('click', e => {
  if (e.target.className.substr(0, 3) == 'num') {
    const tiktoker = e.target.className.substr(4)
    tiktokers = tiktokers.filter(t => t.tiktoker != tiktoker)
    generateTable()
  }
})

const getMarkers = (prop, name) => {
  let max = tiktokersBy[prop].findIndex(t => t.tiktoker == name) + 1
  max = max <= 3 ? `max_${max}` : ''

  let min =
    [...tiktokersBy[prop]].reverse().findIndex(t => t.tiktoker == name) + 1
  min = min <= 3 ? `min_${min}` : ''

  return `${max} ${min}`
}

const tiktokersBy = {}
const generateTable = () => {
  statsBody.innerHTML = ''
  //calculateMinMax()

  const columns = [
    'followerCount',
    'videoCount',
    'heartCount',
    'views',
    'followerPerVideo',
    'heartPerVideo',
    'heartPerFollower',
    'videoStats',
  ]

  columns.forEach(column => {
    tiktokersBy[column] = [...tiktokers].sort((a, b) => b[column] - a[column])
  })

  tiktokers.forEach((tiktoker, i) => {
    statsBody.innerHTML += `<tr>
    <td class="num ${tiktoker.tiktoker}">${i + 1}</td>

    <td>
      <a href="https://www.tiktok.com/${tiktoker.tiktoker}">
          ${tiktoker.tiktoker}
      </a>
    </td>

    <td class="r ${getMarkers('followerCount', tiktoker.tiktoker)}">
      ${Intl.NumberFormat().format(tiktoker.followerCount)}
    </td>

    <td class="r ${getMarkers('videoCount', tiktoker.tiktoker)}">
      ${Intl.NumberFormat().format(tiktoker.videoCount)}
    </td>

    <td class="r ${getMarkers('heartCount', tiktoker.tiktoker)}">
      ${Intl.NumberFormat().format(tiktoker.heartCount)}
    </td>

    <td class="b r ${getMarkers('followerPerVideo', tiktoker.tiktoker)}">
      ${Intl.NumberFormat().format(tiktoker.followerPerVideo.toFixed(0))}
      <small>
        <span>
           ▴ ${Intl.NumberFormat().format(
             (
               tiktoker.followerPerVideo /
               tiktokersBy.followerPerVideo[
                 tiktokersBy.followerPerVideo.length - 1
               ].followerPerVideo
             ).toFixed(0)
           )}x
        </span>
        <span>
           ▴ ${Intl.NumberFormat().format(
             (
               (tiktoker.followerPerVideo /
                 tiktokersBy.followerPerVideo[0].followerPerVideo) *
               100
             ).toFixed(0)
           )} %
        </span>
      </small>
    </td>

    <td class="b r ${getMarkers('heartPerVideo', tiktoker.tiktoker)}">
      ${Intl.NumberFormat().format(tiktoker.heartPerVideo.toFixed(0))}
      <small>
        <span>
        ▴ ${Intl.NumberFormat().format(
          (
            tiktoker.heartPerVideo /
            tiktokersBy.heartPerVideo[tiktokersBy.heartPerVideo.length - 1]
              .heartPerVideo
          ).toFixed(0)
        )}x
      </span>
      <span>
          ▴ ${Intl.NumberFormat().format(
            (
              (tiktoker.heartPerVideo /
                tiktokersBy.heartPerVideo[0].heartPerVideo) *
              100
            ).toFixed(0)
          )} %
      </span>
    </small>
  </td>

    <td class="b r ${getMarkers('heartPerFollower', tiktoker.tiktoker)}">
      ${Intl.NumberFormat().format(tiktoker.heartPerFollower.toFixed(0))}
      <small>
        <span>
        ▴ ${Intl.NumberFormat().format(
          (
            tiktoker.heartPerFollower /
            tiktokersBy.heartPerFollower[
              tiktokersBy.heartPerFollower.length - 1
            ].heartPerFollower
          ).toFixed(0)
        )}x
      </span>
      <span>
          ▴ ${Intl.NumberFormat().format(
            (
              (tiktoker.heartPerFollower /
                tiktokersBy.heartPerFollower[0].heartPerFollower) *
              100
            ).toFixed(0)
          )} %
      </span>
    </small>
    </td>

    <td class="r ${getMarkers('views', tiktoker.tiktoker)}">
      ${Intl.NumberFormat().format(tiktoker.views)}
    </td>

    <td class="r ${getMarkers('videoStats', tiktoker.tiktoker)}">
      ${Intl.NumberFormat().format((tiktoker.videoStats * 100).toFixed(2))}%
    </td>
  </tr>`
  })
}

fetch('http://localhost/~rrd/tiktokers/api.php')
  .then(response => response.json())
  .then(data => {
    tiktokers = data
      .map(tiktoker => ({
        ...tiktoker,
        followerPerVideo: tiktoker.followerCount / tiktoker.videoCount,
        heartPerVideo: tiktoker.heartCount / tiktoker.videoCount,
        heartPerFollower: tiktoker.heartCount / tiktoker.followerCount,
        views: tiktoker.videoStats
          .map(stats => JSON.parse(stats))
          .reduce((acc, cur) => acc + cur.playCount, 0),
        videoStats:
          tiktoker.videoStats
            .map(stats => JSON.parse(stats))
            .map(
              stat =>
                (stat.commentCount + stat.diggCount + stat.shareCount) /
                stat.playCount
            )
            .reduce((acc, cur) => acc + cur, 0) / tiktoker.videoStats.length,
      }))
      .sort((a, b) => (a.tiktoker < b.tiktoker ? -1 : 1))
    generateTable()
  })
