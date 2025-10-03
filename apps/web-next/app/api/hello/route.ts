export async function GET() {
    return new Response(JSON.stringify({ name: 'Allan Rivera' }), {
      headers: { 'content-type': 'application/json' },
    });
  }