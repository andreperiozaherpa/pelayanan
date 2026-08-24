export default function GlassQueueItem({ nomor, service, time, children }) {
  return (
    <div className="queue-item-glass">
      <span className="queue-nomor">{nomor}</span>
      <span className="queue-service">{service}</span>
      <span className="queue-time">{time}</span>
      {children}
    </div>
  )
}
